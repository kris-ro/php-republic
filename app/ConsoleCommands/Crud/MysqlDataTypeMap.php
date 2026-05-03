<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Debug;

class MysqlDataTypeMap {

  private $fieldsData = [];
  private $uniqueFields = [];
  private $autoIncrementFields = [];
  private $primaryKey;
  public $primaryKeyDefinition;
  public $binaryFields;

  /**
   * Get all columns and build the JSON array
   *
   * @param string $tableName
   *
   * @return array
   */
  public function __construct(string $tableName) {
    $stmt = Config::dbModel()
      ->query("DESCRIBE `{$tableName}`")
      ->execute([]);

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      $field = $row['Field'];
      $type = $row['Type'];           // e.g. "smallint(5) unsigned"
      $key = $row['Key'];           // e.g. "smallint(5) unsigned"
      $this->fieldsData[] = $this->parseMySqlColumnType($row);
    }

    // Debug::dump($this->fieldsData);
  }

  public function getFieldsData() {
    return $this->fieldsData;
  }

  public function getUniqueFields() {
    return $this->uniqueFields;
  }

  public function getAutoIncrementFields() {
    return $this->autoIncrementFields;
  }

  public function getPrimaryKey() {
    return $this->primaryKey;
  }

  public function getPrimaryKeyDefinition() {
    return $this->primaryKeyDefinition;
  }

  public function getBinaryFields() {
    return $this->binaryFields;
  }

  /**
   * Maps a MySQL column type string (e.g. "smallint(5) unsigned", "int", "varchar(255)")
   * to a normalized structure: ["name" => "...", "type" => "SMALLINT", "max" => 32767]
   *
   * @param string $columnName
   * @param string $mysqlType
   *
   * @return array
   */
  private function parseMySqlColumnType(array $fieldData): array {
    // Debug::log($fieldData);
    $columnName = $fieldData['Field'];
    $mysqlType = $fieldData['Type'];

    $rawType = trim($mysqlType);
    $upperType = strtoupper($rawType);

    $isOptional = false;
    $defaultValue = false;
    if (($fieldData['Default'] ?? null) !== null || strtoupper($fieldData['Null']) == 'YES') {
      $isOptional = true;
      $defaultValue = $fieldData['Default'] ?? null;
    }

    // Extract base type and parameters
    // preg_match('/^([a-z]+)(?:\(([^)]+)\))?\s*(unsigned)?/i', $rawType, $matches);
    preg_match('/^([a-z0-9_]+)(?:\(([^)]+)\))?\s*(unsigned)?/i', $rawType, $matches);

    $baseType = strtoupper(trim($matches[1] ?? $rawType));
    $paramsStr = $matches[2] ?? '';
    $isUnsigned = !empty($matches[3]);

    // Split parameters (M,D) or enum values
    $params = array_map('trim', explode(',', $paramsStr));
    $length = !empty($params[0]) ? (int) $params[0] : null;
    $scale = isset($params[1]) ? (int) $params[1] : null;

    $normalizedType = $baseType;
    $max = null;            // numeric max (for integers/floats)
    $min = null;            // numeric min (for integers/floats)
    $precision = null;      // for DECIMAL / FLOAT / DOUBLE
    $lengthField = null;    // for strings and binary
    $enumValues = null;
    $extra = [];

    $isPrimaryKey = false;
    if (in_array($fieldData['Key'], ['PRI', 'UNI'])) {
      $this->uniqueFields[] = $columnName;
      if ($fieldData['Key'] == 'PRI') {
        $isPrimaryKey = true;
        $this->primaryKey = $columnName;
      }
    }

    if ($fieldData['Extra'] == 'auto_increment') {
      $this->autoIncrementFields[] = $columnName;
    }

    switch ($baseType) {
      // === Integer types ===
      case 'TINYINT':
        $max = $isUnsigned ? 255 : 127;
        $min = $isUnsigned ? 0 : -128;
        break;

      case 'SMALLINT':
        $max = $isUnsigned ? 65535 : 32767;
        $min = $isUnsigned ? 0 : -32768;
        break;

      case 'MEDIUMINT':
        $max = $isUnsigned ? 16777215 : 8388607;
        $min = $isUnsigned ? 0 : -8388608;
        break;

      case 'INT':
      case 'INTEGER':
        $normalizedType = 'INT';
        $max = $isUnsigned ? 4294967295 : 2147483647;
        $min = $isUnsigned ? 0 : -2147483648;
        break;

      case 'BIGINT':
        $max = $isUnsigned ? pow(2, 64)-1 : pow(2, 63)-1;
        $min = $isUnsigned ? 0 : pow(2, 63) * (-1);
        break;

      // === Floating point ===
      case 'FLOAT':
        $normalizedType = 'FLOAT';
        $precision = $length ?? 24;           // default single precision
        if ($scale !== null) {
          $extra['scale'] = $scale;
        }
        // Force PHP's max value
        $max = 'PHP_FLOAT_MAX';
        $min = 'PHP_FLOAT_MIN';
        break;

      case 'DOUBLE':
        $normalizedType = 'DOUBLE';
        $precision = $length ?? 53;           // default double precision
        if ($scale !== null) {
          $extra['scale'] = $scale;
        }
        // Force PHP's max value
        $max = 'PHP_FLOAT_MAX';
        $min = 'PHP_FLOAT_MIN';
        break;

      // === Exact decimal ===
      case 'DECIMAL':
      case 'NUMERIC':
        $normalizedType = 'DECIMAL';
        $precision = $length ?? 10;           // default if not specified
        $extra['scale'] = $scale ?? 0;
        // Approximate maximum value (as string)
        if ($precision !== null) {
          $max = str_repeat('9', $precision - ($extra['scale'] ?? 0)) .
            ($extra['scale'] > 0 ? '.' . str_repeat('9', $extra['scale']) : '');
        }
        break;

      // === String types ===
      case 'CHAR':
      case 'VARCHAR':
        $normalizedType = $baseType;
        $lengthField = $length ?? 255;        // default for VARCHAR if omitted
        break;

      case 'TINYTEXT':
        $lengthField = 255;
        break;
      case 'TEXT':
        $lengthField = 65535;
        break;
      case 'MEDIUMTEXT':
        $lengthField = 16777215;
        break;
      case 'LONGTEXT':
        $lengthField = 4294967295;
        break;

      // === ENUM ===
      case 'ENUM':
        $normalizedType = 'ENUM';
        if ($paramsStr) {
          // Remove quotes and split values
          $enumValues = array_map(
            fn($v) => trim($v, " '\"\t\n\r"),
            explode(',', $paramsStr)
          );
        }
        $defaultValue = $defaultValue ? '\'' . $defaultValue . '\'' : $defaultValue;
        break;

      // === Date / Time ===
      case 'DATE':
        $normalizedType = 'DATE';
        $extra['format'] = 'YYYY-MM-DD';
        $defaultValue = $defaultValue ? 'date(\'Y-m-d\')' : $defaultValue;
        break;
      case 'DATETIME':
        $normalizedType = 'DATETIME';
        $extra['format'] = 'YYYY-MM-DD HH:MM:SS';
        $defaultValue = $defaultValue ? 'date(\'Y-m-d H:i:s\')' : $defaultValue;
        if ($length !== null) {
          $extra['fractional_seconds'] = $length; // 0-6
        }
        break;
      case 'TIME':
        $normalizedType = 'TIME';
        $extra['format'] = 'HH:MM:SS';
        $defaultValue = $defaultValue ? 'date(\'H:i:s\')' : $defaultValue;
        break;
      case 'TIMESTAMP':
        $normalizedType = 'TIMESTAMP';
        $defaultValue = $defaultValue ? 'date(\'Y-m-d H:i:s\')' : $defaultValue;
        break;

      case 'BINARY':
      case 'VARBINARY':
        $lengthField = $length ?? 255;
        $normalizedType = 'BINARY';
        break;

      default:
        // Unknown / other types (YEAR, SET, BINARY, etc.)
        $normalizedType = $baseType;
        break;
    }

    $result = [
      'name' => $columnName,
      'type' => $normalizedType,
    ];

    if ($max !== null) {
      $result['max'] = $max;
    }
    if ($min !== null) {
      $result['min'] = $min;
    }
    if ($precision !== null) {
      $result['precision'] = $precision;
    }
    if ($lengthField !== null) {
      $result['length'] = $lengthField;
    }
    if ($enumValues !== null) {
      $result['enum_values'] = $enumValues;
    }
    if (in_array($normalizedType, ['TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT'])) {
      $result['unsigned'] = $isUnsigned ? true : false;
    }
    if (!empty($extra)) {
      $result['extra'] = $extra;
    }

    $result['is_optional'] = $isOptional;
    // Debug::log($fieldData);
    $result['default_value'] = $isOptional ? $this->defaultValue($defaultValue) : false;
    $result['primary_key'] = $isPrimaryKey;
    $result['key'] = ($fieldData['Key'] ?? null) ? true : false;


    if ($isPrimaryKey) {
      $this->primaryKeyDefinition = $result;
    }

    if ($normalizedType == 'BINARY') {
      $this->binaryFields[] = $result['name'];
    }

    // original for debugging
    // $result['raw_type'] = $rawType;

    return $result;
  }

  private function defaultValue(mixed $defaultValue): string {
    // Debug::log(gettype($defaultValue));
    switch (strtolower(gettype($defaultValue))) {
      case 'string':
        return empty($defaultValue) ? '\'' . $defaultValue . '\'' : $defaultValue;
      case 'null':
        return 'null';
    }
  }
}
