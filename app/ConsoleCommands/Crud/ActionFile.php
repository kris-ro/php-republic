<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;
use App\ConsoleCommands\Crud\Traits\ActionFileAdd;
use App\ConsoleCommands\Crud\Traits\ActionFileUpdate;
use App\ConsoleCommands\Crud\Traits\ActionFileDelete;

class ActionFile {

  use ActionFileAdd;
  use ActionFileUpdate;
  use ActionFileDelete;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;

  private $htmlPath;

  public function __construct(string $tableName, string $controllerName, array $fields, array $uniqueFields, array $autoIncrementFields) {
    $this->modelName = ucfirst(strtolower($tableName));
    $this->controllerName = $controllerName;
    $this->fields = $fields;
    $this->unique = $uniqueFields;
    $this->autoIncrement = $autoIncrementFields;

    $this->htmlPath = APP_ROOT . DS . 'app' . DS . 'ConsoleCommands' . DS. 'Crud' . DS . 'html' . DS;
  }

  public function buildAction() {
    $this->buildAdd();
    $this->buildUpdate();
    $this->buildDelete();
  }

  public function formElements(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [];

    foreach ($this->fields as $field) {
      if (!$this->primaryKey && $field['primary_key'] == true) {
        $this->primaryKey = $field['name'];
        $this->primaryKeyDefinition = $field;
      }

      if (in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = $this->mapDbTypeToHtmlField($field, $spaceIndent);
    }

    return implode(PHP_EOL, $fields);
  }

  public function detailItems(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [];

    foreach ($this->fields as $field) {
      if (in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = $this->mapDbTypeToDetailItem($field, $spaceIndent);
    }

    return implode(PHP_EOL, $fields);
  }

  private function mapDbTypeToHtmlField(array $field, int $spaceIndent): string {
    switch (strtoupper($field['type'])) {
      case 'TEXT':
      case 'MEDIUMTEXT':
      case 'LONGTEXT':
        return $this->buildTextareaElement($field, $spaceIndent);
      case 'ENUM':
        return $this->buildSelectElement($field, $spaceIndent);
      case 'DATE':
        return $this->buildDateElement($field, $spaceIndent);
      case 'DATETIME':
      case 'TIMESTAMP':
        return $this->buildDateTimeElement($field, $spaceIndent);
      case 'TIME':
        return $this->buildTimeElement($field, $spaceIndent);
      case 'TINYINT':
        return $this->buildSelectElement($field, $spaceIndent, true);
      case 'SMALLINT':
      case 'MEDIUMINT':
      case 'INT':
      case 'INTEGER':
      case 'BIGINT':
      case 'FLOAT':
      case 'DOUBLE':
      case 'DECIMAL':
      case 'NUMERIC':
      case 'CHAR':
      case 'VARCHAR':
      case 'TINYTEXT':
      case 'UUID':
        return $this->buildTextElement($field, $spaceIndent);
      case 'BINARY':
      case 'BLOB':
      case 'LONGBLOB':
        return $this->buildFileElement($field, $spaceIndent);
    }

    return throw new \Exception('Unknown field type: ' . $field['type']);
  }

  private function mapDbTypeToDetailItem(array $field, int $spaceIndent): string {
    switch (strtoupper($field['type'])) {
      case 'TEXT':
      case 'MEDIUMTEXT':
      case 'LONGTEXT':
      case 'ENUM':
      case 'DATE':
      case 'DATETIME':
      case 'TIMESTAMP':
      case 'TIME':
      case 'SMALLINT':
      case 'MEDIUMINT':
      case 'INT':
      case 'INTEGER':
      case 'BIGINT':
      case 'FLOAT':
      case 'DOUBLE':
      case 'DECIMAL':
      case 'NUMERIC':
      case 'CHAR':
      case 'VARCHAR':
      case 'TINYTEXT':
      case 'UUID':
        return $this->displayText($field, $spaceIndent);
      case 'TINYINT':
        return $this->displayBoolean($field, $spaceIndent);
      case 'BINARY':
      case 'BLOB':
      case 'LONGBLOB':
        return $this->displayDownload($field, $spaceIndent);
    }

    return throw new \Exception('Unknown field type: ' . $field['type']);
  }

  private function buildTextareaElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'textarea.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildSelectElement(array $field, int $spaceIndent, bool $boolean = false): string {
    if ($boolean) {
      $values = ['1' => 'Yes', '0' => 'No'];

    } else {
      $values = [];
      foreach ($field['enum_values'] as $value) {
        $values[$value] = Strings::prettify($value);
      }
    }

    return Template::load($this->htmlPath . 'select.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
      'values' => $values,
      'selected' => '<?php echo Request::post(\'' . $field['name'] . '\') ?>',
    ]);
  }

  private function buildDateElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'date.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildDateTimeElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'datetime.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildTimeElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'time.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildTextElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'text.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildFileElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'file.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function displayText(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'display_text.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function displayBoolean(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'display_boolean.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function displayDownload(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'display_binary.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }
}