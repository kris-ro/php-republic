<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits\ConfigFileRouting;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits\ConfigFilePost;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits\ConfigFileMenu;

class ConfigFile {

  use ConfigFileRouting;
  use ConfigFilePost;
  use ConfigFileMenu;

  private $modelName;
  private $controllerName;
  private $fields;
  private $unique;
  private $autoIncrement;
  private $primaryKey;
  private $primaryKeyDefinition;
  private $binaryFields;

  private $jsonRoutingPath;
  private $jsonPostPath;

  private $htmlPath;
  private $htmlMenuPath;

  public function __construct(\KrisRo\PhpRepublic\ConsoleCommands\Crud $crud) {
    $this->modelName = $crud->modelName;
    $this->controllerName = $crud->controllerName;
    $this->fields = $crud->fields;
    $this->unique = $crud->unique;
    $this->autoIncrement = $crud->autoIncrement;
    $this->primaryKey = $crud->primaryKey;
    $this->primaryKeyDefinition = $crud->primaryKeyDefinition;
    $this->binaryFields = $crud->binaryFields;

    $this->jsonRoutingPath = APP_ROOT . DS . 'app' . DS . 'Config' . DS. 'routes' . DS . 'admin' . DS . 'authenticated.json';
    $this->jsonPostPath = APP_ROOT . DS . 'app' . DS . 'Config' . DS. 'post.json';
    $this->htmlMenuPath = APP_ROOT . DS . 'app' . DS . 'views' . DS . 'templates' . DS . 'admin' . DS . 'en' . DS. 'side_menu.php';

    $this->htmlPath = $crud->htmlPath;
  }

  public function updateConfig() {
    $this->updateRouting();
    $this->updatePost();
    $this->updateMenu();
  }
}