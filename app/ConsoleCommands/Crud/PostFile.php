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
  private $binaryFields;
  private $postPath;


  private $validationMethods = [];

  private $postFiles = [];

  public function __construct(\App\ConsoleCommands\Crud $crud) {
    $this->modelName = $crud->modelName;
    $this->controllerName = $crud->controllerName;
    $this->fields = $crud->fields;
    $this->unique = $crud->unique;
    $this->autoIncrement = $crud->autoIncrement;
    $this->primaryKey = $crud->primaryKey;
    $this->primaryKeyDefinition = $crud->primaryKeyDefinition;
    $this->binaryFields = $crud->binaryFields;
    $this->postPath = $crud->postPath;
  }

  public function buildPost() {
    if (!file_exists($this->postPath)) {
      mkdir($this->postPath);
      chmod($this->postPath, 0755);
    }
    
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
        return $this->buildVarcharValidationRule($field);
      case 'TINYTEXT':
      case 'TEXT':
      case 'MEDIUMTEXT':
      case 'LONGTEXT':
        return $this->buildTextValidationRule($field);
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
        return $this->buildBinaryValidationRule($field);
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
    
    if ($field['is_optional']) {
      $rules[] = '\'is_string\'';
    } else {
      $rules[] = '\'notEmptyOneLineString\'';
    }
    
    $rules[] = '[\'maxLength\', ' . ($field['length'] ?? 255) . ']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildTextValidationRule(array $field) {
    $rules = [];
    
    if ($field['is_optional']) {
      $rules[] = '\'is_string\'';
    } else {
      $rules[] = '\'mandatoryText\'';
    }
    
    $rules[] = '[\'maxLength\', ' . ($field['length'] ?? 255) . ']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function buildBinaryValidationRule(array $field) {
    $rules = [];
    $rules[] = '\'is_string\'';
    $rules[] = '[\'maxLength\', ' . (($field['length'] ?? 255) * 2) . ']';

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
    $this->postFiles[$field['name']] = $field['name'];

    $rules = [];
    $rules[] = '[\'validFileInput\']';

    return '[' . implode(', ', $rules + $this->isOptional($field)) . '],';
  }

  private function isOptional(array $field) {
    if ($field['is_optional']) {
      return [999 => '\'isOptional\''];
    }

    return [];
  }

  private function collectFileFields() {
    if (empty($this->postFiles)) {
      return '';
    }

    $content = '';

    foreach ($this->postFiles as $fileField) {
      $content .= '    $post[\'' . $fileField . '\'] = $post[\'' . $fileField . '\'][\'name\'] ? file_get_contents($post[\'' . $fileField . '\'][\'tmp_name\']) : null;' . PHP_EOL;
    }

    foreach ($this->binaryFields as $binaryField) {
      $content .= '    $post[\'' . $binaryField . '\'] = hex2bin($post[\'' . $binaryField .'\']);' . PHP_EOL;
    }

    return $content . PHP_EOL;
  }

  private function setDefaultValues(array|null $excludedFields = []) {
    $content = '';

    foreach ($this->fields as $field) {
      if (in_array($field['name'], $excludedFields) || !$field['is_optional']) {
        continue;
      }

      $content .= '    if (empty($post[\'' . $field['name'] . '\'])) {' . PHP_EOL
                . '      $post[\'' . $field['name'] . '\'] = ' . $field['default_value'] . ';' . PHP_EOL
                . '    }' . PHP_EOL;
    }

    return $content . PHP_EOL;
  }
}