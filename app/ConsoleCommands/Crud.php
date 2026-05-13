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
  public $slimTableFields;

  private $valid = true;

  public $htmlPath = APP_ROOT . DS . 'app' . DS . 'ConsoleCommands' . DS. 'Crud' . DS . 'html' . DS;

  public $modelPath = '';
  public $controllerPath = '';
  public $postPath = '';
  public $actionPath = '';
  public $adminViewPath = '';

  public function __construct(string|null $model = null, bool|null $force = null) {
    $this->setModel($model);

    if ($this->filesExists($force ? true : false)) {
      self::echoError('At least some of the files exists. If you want to overrite them please use the --force=1 flag');
      $this->valid = false;

      return $this;
    }
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
    // Debug::dump('');

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
      $this->slimTableFields = $model->getSlimTableFields();

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

    (new ControllerFile($this))->buildController();
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

  private function filesExists(bool $force): bool {
    if (!$this->valid) {
      return $this;
    }

    $this->controllerName = ucfirst(Strings::toCamelCase($this->modelName)) . 's';

    $this->modelPath = APP_ROOT . DS . 'app' . DS . 'Models' . DS . Strings::toCamelCase($this->modelName) . '.php';
    $this->controllerPath = APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . Strings::toCamelCase($this->controllerName) . '.php';
    $this->postPath = APP_ROOT . DS . 'app' . DS . 'Post' . DS . $this->controllerName;
    $this->actionPath = APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName;
    $this->adminViewPath = APP_ROOT . DS . 'app' . DS . 'views' . DS . 'admin' . DS . 'en' . DS . strtolower($this->controllerName);

    if ($force) {
      return false;
    }

    if (file_exists($this->modelPath)) {
      return true;
    }

    if (file_exists($this->controllerPath)) {
      return true;
    }

    if (file_exists($this->postPath)) {
      return true;
    }

    if (file_exists($this->actionPath)) {
      return true;
    }

    if (file_exists($this->adminViewPath)) {
      return true;
    }

    return false;
  }
}
