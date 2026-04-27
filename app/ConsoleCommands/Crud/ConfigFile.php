<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;
use App\ConsoleCommands\Crud\Traits\ConfigFileRouting;
use App\ConsoleCommands\Crud\Traits\ConfigFilePost;

class ConfigFile {

  use ConfigFileRouting;
  use ConfigFilePost;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;

  private $jsonRoutingPath;
  private $jsonPostPath;

  public function __construct(string $tableName, string $controllerName, array $fields, array $uniqueFields, array $autoIncrementFields) {
    $this->modelName = ucfirst(strtolower($tableName));
    $this->controllerName = $controllerName;
    $this->fields = $fields;
    $this->unique = $uniqueFields;
    $this->autoIncrement = $autoIncrementFields;

    $this->jsonRoutingPath = APP_ROOT . DS . 'app' . DS . 'Config' . DS. 'routes' . DS . 'admin' . DS . 'authenticated.json';
    $this->jsonPostPath = APP_ROOT . DS . 'app' . DS . 'Config' . DS. 'post.json';
  }

  public function updateConfig() {
    $this->updateRouting();
    $this->updatePost();
  }
}