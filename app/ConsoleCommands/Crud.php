<?php

namespace App\ConsoleCommands;

use KrisRo\Validator\Validator;
use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpRepublic\Strings;
use App\ConsoleCommands\Crud\MysqlDataTypeMap;
use App\ConsoleCommands\Crud\ModelFile;
use App\ConsoleCommands\Crud\ControllerFile;
use App\ConsoleCommands\Crud\PostFile;
use App\ConsoleCommands\Crud\ActionFile;
use App\ConsoleCommands\Crud\ConfigFile;

class Crud {

  use ConsoleIO;

  public $model;
  public $modelName;
  public $controllerName;
  public $actionName;
  public $fields = [];
  public $unique = [];
  public $autoIncrement = [];
  public $primaryKey;
  public $primaryKeyDefinition;
  public $binaryFields;

  private $valid = true;

  public $htmlPath = APP_ROOT . DS . 'app' . DS . 'ConsoleCommands' . DS. 'Crud' . DS . 'html' . DS;

  public function __construct(string|null $model = null) {
    $this->setModel($model);
  }

  public function setModel(string|null $model = null) {
    if (!$model) {
      throw new \Exception('Invalid model supplied in console crud command');
    }

    $validName = (new Validator(Config::get('app/validation_rules')))
      ->value($model)
      ->addValidationRule('model_name')
      ->process();

    if (!$validName) {
      self::echoError('Invalid name for a table. It needs to start with a letter, not end with an underscore and contain only letters, numbers and underscore');
      $this->valid = false;
    } else {
      $this->model = $model;
      $this->modelName = ucfirst(strtolower($model));
    }

    return $this;
  }

  public function create() {
    if (!$this->valid) {
      return $this;
    }

    $this->createModel();
    $this->createController();
    $this->createPost();
    $this->createAction();
    $this->updateRoutingAndPost();

    return $this;
  }

  public function createModel() {
    try {
      $model = new MysqlDataTypeMap($this->model);
      $this->fields = $model->getFieldsData();
      // Debug::dump($this->fields);
      $this->unique = $model->getUniqueFields();
      $this->autoIncrement = $model->getAutoIncrementFields();

      $this->primaryKey = $model->getPrimaryKey();
      $this->primaryKeyDefinition = $model->getPrimaryKeyDefinition();
      $this->binaryFields = $model->getBinaryFields();

    } catch (\PDOException $e) {
      self::echoError($e->getMessage());
      Config::logger()->error($e->getMessage());

      $this->valid = false;
      return $this;
    }

    try {
      (new ModelFile($this))->buildModel();
    } catch(\Exception $e) {
      self::echoError($e->getMessage());
      Config::logger()->error($e->getMessage());

      $this->valid = false;
    }

    return $this;
  }

  public function createController() {
    if (!$this->valid) {
      return;
    }

    $this->controllerName = (new ControllerFile($this->modelName))->buildController();
  }

  public function createPost() {
    if (!$this->valid) {
      return;
    }

    (new PostFile($this))->buildPost();
  }

  public function createAction() {
    if (!$this->valid) {
      return;
    }

    (new ActionFile($this))->buildAction();
  }

  public function updateRoutingAndPost() {
    if (!$this->valid) {
      return;
    }

    (new ConfigFile($this))->updateConfig();
  }
}
