<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;
use App\ConsoleCommands\Crud\Traits\ActionFileAdd;
use App\ConsoleCommands\Crud\Traits\ActionFileUpdate;
use App\ConsoleCommands\Crud\Traits\ActionFileDelete;
use App\ConsoleCommands\Crud\Traits\ActionFileList;

class ActionFile {

  use ActionFileAdd;
  use ActionFileUpdate;
  use ActionFileDelete;
  use ActionFileList;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;
  private $binaryFields;

  private $htmlPath;

  public function __construct(\App\ConsoleCommands\Crud $crud) {
    $this->modelName = $crud->modelName;
    $this->controllerName = $crud->controllerName;
    $this->fields = $crud->fields;
    $this->unique = $crud->unique;
    $this->autoIncrement = $crud->autoIncrement;
    $this->primaryKey = $crud->primaryKey;
    $this->primaryKeyDefinition = $crud->primaryKeyDefinition;
    $this->binaryFields = $crud->binaryFields;

    $this->htmlPath = $crud->htmlPath;
  }

  public function buildAction() {
    $this->buildAdd();
    $this->buildUpdate();
    $this->buildDelete();
    $this->buildList();
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

  public function listFilters(int $spaceIndent): string {
    $filters = [];

    $indentation = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT);

    foreach ($this->fields as $field) {
      if (!($field['key'] ?? null)) {
        continue;
      }

      // 'alias' => ['db.field_name', []],
      $filters[] = $indentation . '  '
                   . '\'' . $field['name'] . '\' => [\'' . $field['name'] . '\', []],';
    }

    return $indentation . 'private $filters = [' . PHP_EOL
                   . implode(PHP_EOL, $filters) . PHP_EOL
           . $indentation . '];' . PHP_EOL;
  }

  private function listingObjectFilters(int $spaceIndent): string {
    $filters = [];

    $table = strtolower($this->modelName);
    $indentation = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT);

    foreach ($this->fields as $field) {
      if (!($field['key'] ?? null)) {
        continue;
      }

      $filters[] = $this->listingObjectFiltersItem($table, $field, $indentation);
    }

    return implode(PHP_EOL, $filters) . ';' . PHP_EOL;
  }

  private function listingObjectDataPrep(int $spaceIndent): string {
    $indentation = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT);

    $items = [];
    foreach ($this->fields as $field) {
      $items[] = $this->listingObjectDataPrepItem($field, $indentation);
    }

    return implode(PHP_EOL, array_filter($items));
  }

  private function listTableHeader() {
    $cols = [];

    foreach ($this->fields as $field) {
      $cols[] = Template::load($this->htmlPath . 'list_header.php', [
        'name' => $field['name'],
        'key' => $field['key'],
        'label' => Strings::prettify($field['name']),
      ]);
    }

    return implode(PHP_EOL, $cols);
  }

  private function listTableSearch() {
    $cols = [];

    foreach ($this->fields as $field) {
      $type = 'TEXT';

      if (in_array($field['type'], ['ENUM', 'TINYINT'])) {
        $type = 'SELECT';
      }

      if (in_array($field['type'], ['DATE', 'DATETIME', 'TIMESTAMP'])) {
        $type = 'DATE';
      }

      if ($field['type'] == 'TIME') {
        $type = 'TIME';
      }

      $values = [];
      if (in_array($field['type'], ['TINYINT'])) {
        $values = ['1' => 'Yes', '0' => 'No'];
      }

      if ($field['type'] == 'ENUM') {
        foreach ($field['enum_values'] as $value) {
          $values[$value] = Strings::prettify($value);
        }
      }

      $cols[] = Template::load($this->htmlPath . 'list_search.php', [
        'name' => $field['name'],
        'key' => $field['key'],
        'label' => Strings::prettify($field['name']),
        'type' => $type,
        'options' => $values,
      ]);
    }

    return implode(PHP_EOL, $cols);
  }

  private function listTableItems() {
    return Template::load($this->htmlPath . 'list_row.php', [
      'fields' => $this->fields,
      'controller' => strtolower($this->controllerName),
      'primary_key' => $this->primaryKey,
    ]);
  }

  private function listTableFooter() {
    $cols = [];

    foreach ($this->fields as $field) {
      $cols[] = Template::load($this->htmlPath . 'list_footer.php', [
        'label' => Strings::prettify($field['name']),
      ]);
    }

    return implode(PHP_EOL, $cols);
  }

  private function listingObjectFiltersItem(string $table, array $field, string $indentation): string {
    // ->filter('user_tokens.fingerprint', 'fingerprint', 'LIKE')
    // return $indentation . '->filter(\'' . $table . '.' . $field['name'] . '\', \'' . $field['name'] . '\')';
    switch (strtoupper($field['type'])) {
      case 'TEXT':
      case 'MEDIUMTEXT':
      case 'LONGTEXT':
      case 'CHAR':
      case 'VARCHAR':
      case 'TINYTEXT':
        return $indentation . '->filter(\'' . $table . '.' . $field['name'] . '\', \'' . $field['name'] . '\', \'LIKE\')';
      case 'DATE':
      case 'DATETIME':
      case 'TIMESTAMP':
      case 'TIME':
        return $indentation . '->filter(\'' . $table . '.' . $field['name'] . '\', \'' . $field['name'] . '\', \'=\', \'date\')';
    }

    return $indentation . '->filter(\'' . $table . '.' . $field['name'] . '\', \'' . $field['name'] . '\')';
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
        return $this->buildTextareaElement($field, $spaceIndent);
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
        return $this->displayText($field, $spaceIndent);
      case 'BLOB':
      case 'LONGBLOB':
        return $this->displayDownload($field, $spaceIndent);
    }

    return throw new \Exception('Unknown field type: ' . $field['type']);
  }

  private function listingObjectDataPrepItem(array $field, string $indentation): string {
    switch (strtoupper($field['type'])) {
      case 'DATE':
      case 'DATETIME':
      case 'TIMESTAMP':
        return $indentation . '\'' . $field['name'] . '\' => Dates::format($item[\'' . $field['name'] . '\']),';
      case 'TIME':
        return $indentation . '\'' . $field['name'] . '\' => Dates::format($item[\'' . $field['name'] . '\'], \'H:i:s\'),';
      case 'TINYINT':
        return $indentation . '\'' . $field['name'] . '\' => $item[\'' . $field['name'] . '\'] ? \'Yes\' : \'No\',';
      case 'BINARY':
      case 'VARBINARY':
        return $indentation . '\'' . $field['name'] . '\' => bin2hex($item[\'' . $field['name'] . '\']),';
    }

    return '';
  }

  private function buildTextareaElement(array $field, int $spaceIndent): string {
    return Template::load($this->htmlPath . 'textarea.php', [
      'indent' => str_pad('', $spaceIndent, ' ', STR_PAD_LEFT),
      'name' => $field['name'],
      'label' => Strings::prettify($field['name']),
    ]);
  }

  private function buildSelectElement(array $field, int $spaceIndent, bool $boolean = false): string {
    $default = '';
    if ($boolean) {
      $values = ['1' => 'Yes', '0' => 'No'];
      $default = '0';

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
      'default' => $default,
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

  private function binary2hex(int $spaceIndent, string $itemName) {
    $indentation = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT);

    if (empty($this->binaryFields)) {
      return '';
    }

    $content = '';
    foreach ($this->binaryFields as $field) {
      $content .= $indentation . '$' . $itemName . '[\'' . $field . '\'] = bin2hex($' . $itemName . '[\'' . $field . '\']);' . PHP_EOL;
    }

    return $content;
  }
}