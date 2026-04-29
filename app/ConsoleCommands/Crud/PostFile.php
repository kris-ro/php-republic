<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use App\ConsoleCommands\Crud\Traits\PostFileAdd;
use App\ConsoleCommands\Crud\Traits\PostFileUpdate;
use App\ConsoleCommands\Crud\Traits\PostFileDelete;

class PostFile {

  use PostFileAdd;
  use PostFileUpdate;
  use PostFileDelete;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;
  private $actionName;

  private $validationMethods = [];

  public function __construct(string $tableName, string $controllerName, array $fields, array $uniqueFields, array $autoIncrementFields) {
    $this->modelName = ucfirst(strtolower($tableName));
    $this->controllerName = $controllerName;
    $this->fields = $fields;
    $this->unique = $uniqueFields;
    $this->autoIncrement = $autoIncrementFields;
  }

  public function buildPost() {
    $this->buildAdd();
    $this->buildUpdate();
    $this->buildDelete();
  }

  private function buildFieldsMessages(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [
      str_pad('', $spaceIndent, ' ', STR_PAD_LEFT) . 'self::INPUT_ELEMENT_NAME => Translate::csrf(\'Invalid Form\'),'
    ];

    foreach ($this->fields as $field) {
      if (in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT)
                . '\'' . $field['name'] . '\' => Translate::' . strtolower($this->modelName) . '(\'Invalid ' . strtolower(str_replace('_', ' ', $field['name'])) . '\'),';
    }

    return implode(PHP_EOL, $fields);
  }

  private function buildFieldsValidation(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [
      str_pad('', $spaceIndent, ' ', STR_PAD_LEFT) . 'self::INPUT_ELEMENT_NAME => [[\'App\\\\Post\\\\' . $this->controllerName . '\\\\' . $this->actionName .'\', \'validFormToken\']],'
    ];

    foreach ($this->fields as $field) {
      if (!$this->primaryKey && $field['primary_key'] == true) {
        $this->primaryKey = $field['name'];
        $this->primaryKeyDefinition = $field;
      }

      if (in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT)
                . '\'' . $field['name'] . '\' => ' . $this->mapDbTypeToValidationRule($field);
    }

    return implode(PHP_EOL, $fields);
  }

  private function mapDbTypeToValidationRule(array $field): string {
    switch (strtoupper($field['type'])) {
      case 'TINYINT':
      case 'SMALLINT':
      case 'MEDIUMINT':
      case 'INT':
      case 'INTEGER':
      case 'BIGINT':
        return $this->buildIntegerValidationRule($field);
      case 'FLOAT':
      case 'DOUBLE':
        return $this->buildFloatValidationRule($field);
      case 'DECIMAL':
      case 'NUMERIC':
        return $this->buildNumericalValidationRule($field);
      case 'CHAR':
      case 'VARCHAR':
      case 'TINYTEXT':
      case 'TEXT':
      case 'MEDIUMTEXT':
      case 'LONGTEXT':
        return $this->buildVarcharValidationRule($field);
      case 'ENUM':
        return $this->buildListValidationRule($field);
      case 'DATE':
        return $this->buildDateValidationRule($field);
      case 'DATETIME':
      case 'TIMESTAMP':
        return $this->buildDateTimeValidationRule($field);
      case 'TIME':
        return $this->buildTimeValidationRule($field);
      case 'UUID':
        return $this->buildUuidValidationRule($field);
      case 'BINARY':
      case 'BLOB':
      case 'LONGBLOB':
        return $this->buildLongBlobValidationRule($field);
    }

   throw new \Exception('Unknown field type');
  }

  private function buildIntegerValidationRule(array $field) {
    $min = ($field['unsigned'] ?? false) ? 0 : $field['min'];
    $max = min($field['max'], PHP_INT_MAX);

    $rules = [];
    $rules[] = $field['unsigned'] ? '\'positiveInteger\'' : '\'integer\'';
    $rules[] = '[\'between\', \'lowerLimit\' => ' . $min . ', \'upperLimit\' => ' . $max . ']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildFloatValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'float\'';
    $rules[] = '[\'smallerThan\', ' . $field['max'] . ']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildNumericalValidationRule(array $field) {
    $rules = [];
    $rules[] = '[\'float\']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildVarcharValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[\'maxLength\', ' . ($field['length'] ?? 255) . ']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildListValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[new ' . $this->actionName . '(), \'valid' . ucfirst(Strings::toCamelCase($field['name'])) . '\']';

    $this->validationMethods[] = PHP_EOL
      . '  public function valid' . ucfirst(Strings::toCamelCase($field['name'])) . '($value, $post) {' . PHP_EOL
      . '    $acceptedValues = [\'' . implode('\', \'', $field['enum_values']) . '\'];' . PHP_EOL . PHP_EOL
      . '    if (in_array($value, $acceptedValues)) {' . PHP_EOL
      . '      return true;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    return false;' . PHP_EOL
      . '  }';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildDateValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '\'isValidDate\'';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildDateTimeValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[\'KrisRo\\\\PhpRepublic\\\\Dates\', \'isValidMySqlDateTime\']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildTimeValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[\'KrisRo\\\\PhpRepublic\\\\Dates\', \'isValidMySqlTime\']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildUuidValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[new ' . $this->actionName . '(), \'valid' . ucfirst(Strings::toCamelCase($field['name'])) . '\']';

    $this->validationMethods[] = PHP_EOL
      . '  public function valid' . ucfirst(Strings::toCamelCase($field['name'])) . '($value, $post) {' . PHP_EOL
      . '    $uuidPattern = \'/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i\';' . PHP_EOL . PHP_EOL
      . '    if (preg_match($uuidPattern, $value)) {' . PHP_EOL
      . '      return true;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    return false;' . PHP_EOL
      . '  }';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildLongBlobValidationRule(array $field) {
    $rules = [];
    $rules[] = '[new ' . $this->actionName . '(), \'valid' . ucfirst(Strings::toCamelCase($field['name'])) . '\']';

    $this->validationMethods[] = PHP_EOL
      . '  public function valid' . ucfirst(Strings::toCamelCase($field['name'])) . '($value, $post) {' . PHP_EOL
      . '    if (empty($_FILES) && empty($_POST)) {' . PHP_EOL
      . '      Config::validator()->setPostValidationMessage(\'' . $field['name'] . '\', Translate::files(\'File is way to big. Max file size is @MAXFILEZISE\', [\'@MAXFILEZISE\' => ini_get(\'upload_max_filesize\')]));' . PHP_EOL
      . '      return false;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    if (!($_FILES[\'' . $field['name'] . '\'][\'tmp_name\'] ?? null)) {' . PHP_EOL
      . '      Config::validator()->setPostValidationMessage(\'' . $field['name'] . '\', Translate::files(\'No file was uploaded\'));' . PHP_EOL
      . '      return false;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    if (!isset($_FILES[\'' . $field['name'] . '\'][\'error\'])) {' . PHP_EOL
      . '      Config::validator()->setPostValidationMessage(\'' . $field['name'] . '\', Translate::files(\'Unknown upload error\'));' . PHP_EOL
      . '      return false;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    if (!isset($_FILES[\'' . $field['name'] . '\'][\'error\']) || $_FILES[\'' . $field['name'] . '\'][\'error\'] != UPLOAD_ERR_OK) {' . PHP_EOL
      . '      Config::validator()->setPostValidationMessage(\'' . $field['name'] . '\', Translate::files(\'Error: @UPLOAD_ERROR\', [\'@UPLOAD_ERROR\' => Files::errorMessage($_FILES[\'' . $field['name'] . '\'][\'error\'])]));' . PHP_EOL
      . '      return false;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    if (isset($_FILES[\'' . $field['name'] . '\'][\'size\']) && $_FILES[\'' . $field['name'] . '\'][\'size\'] == 0) {' . PHP_EOL
      . '      Config::validator()->setPostValidationMessage(\'' . $field['name'] . '\', Translate::files(\'File is way to big. Max file size is @MAXFILEZISE\', [\'@MAXFILEZISE\' => ini_get(\'upload_max_filesize\')]));' . PHP_EOL
      . '      return false;' . PHP_EOL
      . '    }' . PHP_EOL . PHP_EOL
      . '    return true;' . PHP_EOL
      . '  }';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function isOptional(array $field) {
    if ($field['is_optional']) {
      return [999 => '\'isOptional\''];
    }

    return [];
  }
}