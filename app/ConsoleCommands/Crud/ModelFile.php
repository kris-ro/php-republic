<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;

class ModelFile {

  private $modelName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;
  private $binaryFields;
  private $modelPath;

  public function __construct(\App\ConsoleCommands\Crud $crud) {
    $this->modelName = ucfirst(strtolower($crud->modelName));
    $this->fields = $crud->fields;
    $this->unique = $crud->unique;
    $this->autoIncrement = $crud->autoIncrement;
    $this->primaryKey = $crud->primaryKey;
    $this->primaryKeyDefinition = $crud->primaryKeyDefinition;
    $this->binaryFields = $crud->binaryFields;
    $this->modelPath = $crud->modelPath;
  }

  public function buildModel() {
    $fileContent = '<?php'
                     . PHP_EOL . PHP_EOL
                     . 'namespace App\Models;' . PHP_EOL . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Messages;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Translate;' . PHP_EOL . PHP_EOL
                     . 'class ' . Strings::toCamelCase($this->modelName) . ' extends \KrisRo\PhpRepublic\Model {' . PHP_EOL . PHP_EOL
                     . '  public function set' . Strings::toCamelCase($this->modelName) . '(array $data): int {' . PHP_EOL
                     . '    return $this->db->set' . $this->modelName . '([' . PHP_EOL
                     .        $this->buildFields(6) . PHP_EOL
                     . '    ]);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL;

    foreach ($this->unique as $uniqueField) {
      $fileContent  .= '  public function update' . Strings::toCamelCase($this->modelName) . 'By' . Strings::toCamelCase(ucfirst(strtolower($uniqueField))) . '(array $data) {' . PHP_EOL
                     . '    $criteria = [' . PHP_EOL
                     . '      \'condition\' => \'`' . $uniqueField . '` = :' . $uniqueField . '\',' . PHP_EOL
                     . '      \'params\' => [\':' . $uniqueField . '\' => $data[\'' . $uniqueField . '\']],' . PHP_EOL
                     . '      \'values\' => [' . PHP_EOL
                     .          $this->buildFields(8, [$uniqueField]) . PHP_EOL
                     . '      ],' . PHP_EOL
                     . '    ];' . PHP_EOL . PHP_EOL
                     . '    return $this->db->update' . $this->modelName . 'ByCondition($criteria);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL;
    }

    foreach ($this->unique as $uniqueField) {
      $uniqueField = ucfirst(strtolower($uniqueField));

      $fileContent  .= '  public function get' . Strings::toCamelCase($this->modelName) . 'By' . Strings::toCamelCase($uniqueField) . '($value): array|null {' . PHP_EOL
                     . '    return $this->db->getAssoc' . $this->modelName . 'By' . $uniqueField . '($value)->next() ?: null;' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL;

      $fileContent  .= '  public function delete' . Strings::toCamelCase($this->modelName) . 'By' . Strings::toCamelCase($uniqueField) . '($value) {' . PHP_EOL
                     . '    return $this->db->delete' . $this->modelName . 'By' . $uniqueField . '($value);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL;
    }

    $fileContent    .= '}' . PHP_EOL;

    file_put_contents($this->modelPath, $fileContent . PHP_EOL);
  }

  private function buildFields(int $spaceIndent, array|null $excludedFields = []) {
    $fields = [];

    foreach ($this->fields as $field) {
      if (in_array($field['name'], $this->autoIncrement) || in_array($field['name'], $excludedFields)) {
        continue;
      }

      $fields[] = str_pad('', $spaceIndent, ' ', STR_PAD_LEFT)
                . '\'' . $field['name'] . '\' =>  $data[\'' . $field['name'] . '\'],';
    }

    return implode(PHP_EOL, $fields);
  }
}