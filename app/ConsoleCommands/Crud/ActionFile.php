<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;
use App\ConsoleCommands\Crud\Traits\ActionFileAdd;
// use App\ConsoleCommands\Crud\Traits\ActionFileUpdate;
// use App\ConsoleCommands\Crud\Traits\ActionFileDelete;

class ActionFile {

  use ActionFileAdd;
  // use ActionFileUpdate;
  // use ActionFileDelete;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;

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
    // $this->buildUpdate();
    // $this->buildDelete();
  }

  public function formElements(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [];

    foreach ($this->fields as $field) {
      if (in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = $this->mapDbTypeToHtmlField($field, $spaceIndent);
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
    }

    return '<>';
//    return throw new \Exception('Unknown field type');
  }

  private function buildTextareaElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'textarea.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
      'value' => '<?php echo Request::post(\'' . $field['name'] . '\') ?>',
    ]);
  }

  private function buildSelectElement(array $field, int $spaceIndent): string {
    $values = [];

    foreach ($field['enum_values'] as $value) {
      $values[$value] = Strings::prettify($value);
    }

    return Template::load($this->htmlPath . 'select.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
      'values' => $values,
      'selected' => '<?php echo Request::post(\'' . $field['name'] . '\') ?>',
    ]);
  }
}