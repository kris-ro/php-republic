<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
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

  public function __construct(string $tableName, string $controllerName, array $fields, array $uniqueFields, array $autoIncrementFields) {
    $this->modelName = ucfirst(strtolower($tableName));
    $this->controllerName = $controllerName;
    $this->fields = $fields;
    $this->unique = $uniqueFields;
    $this->autoIncrement = $autoIncrementFields;
  }

  public function buildAction() {
    $this->buildAdd();
    // $this->buildUpdate();
    // $this->buildDelete();
  }
}