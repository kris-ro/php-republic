<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpConfig\Config;

class ValidatorFile {

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;
  private $actionName;
  private $binaryFields;

  private $warning;
  private $error;
  private $message;

  public function __construct(\KrisRo\PhpRepublic\ConsoleCommands\Crud $crud) {
    $this->modelName = $crud->modelName;
    $this->controllerName = $crud->controllerName;
    $this->fields = $crud->fields;
    $this->unique = $crud->unique;
    $this->autoIncrement = $crud->autoIncrement;
    $this->primaryKey = $crud->primaryKey;
    $this->primaryKeyDefinition = $crud->primaryKeyDefinition;
    $this->binaryFields = $crud->binaryFields;
  }

  public function addValidationMethod() {
    $filePath = APP_ROOT . DS . 'app' . DS . 'Validator.php';
    
    if (Config::get('app/validator') != 'App\\Validator' || !file_exists($filePath)) {
      $this->warning = 'Can not find App\Validator, you need to add entity validator method manually';
      return false;
    }

    if (!($file = file_get_contents($filePath))) {
      $this->warning = 'Can not load file at ' . $filePath . '; you need to add entity validator method manually';
      return false;
    }

    if (method_exists('\\App\\Validator', 'valid' . ucfirst(Strings::toCamelCase($this->primaryKey)))) {
      $this->message = 'Method already exists \\App\\Validator::valid' . ucfirst(Strings::toCamelCase($this->primaryKey)) . '(). Skipping...';
      return false;
    }

    $file = preg_replace('%(// DO NOT DELETE THIS LINE //)%', $this->getMethodDefinition() . '  $1', $file);

    return file_put_contents($filePath, $file) ? true : false;
  }

  private function getMethodDefinition() {
    return 
      PHP_EOL
    . '  public function valid' . ucfirst(Strings::toCamelCase($this->primaryKey)) . '($value) {' . PHP_EOL
    . '    $id = current(explode(\'|\', $value ?? \'\') ?: []);' . PHP_EOL . PHP_EOL

    . '    return !empty((new \App\Models\\' . Strings::toCamelCase($this->modelName) . '())->get' . Strings::toCamelCase($this->modelName) . 'By' . ucfirst(Strings::toCamelCase($this->primaryKey)) . '($id));' . PHP_EOL
    . '  }' . PHP_EOL . PHP_EOL;
  }

  public function getWarning() {
    return $this->warning;
  }

  public function getError() {
    return $this->error;
  }

  public function getMessage() {
    return $this->message;
  }
}

?>