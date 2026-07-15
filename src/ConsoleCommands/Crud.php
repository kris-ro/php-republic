<?php

namespace KrisRo\PhpRepublic\ConsoleCommands;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\MysqlDataTypeMap;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\ModelFile;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\ControllerFile;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\PostFile;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\ActionFile;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\ValidatorFile;
use KrisRo\PhpRepublic\ConsoleCommands\Crud\ConfigFile;

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

  public $htmlPath = APP_ROOT . DS . 'src' . DS . 'ConsoleCommands' . DS. 'Crud' . DS . 'html' . DS;

  public $modelPath = '';
  public $controllerPath = '';
  public $postPath = '';
  public $actionPath = '';
  public $adminViewPath = '';

  private $entityValidator = false;

  public function __construct(string|null $model = null, bool|null $validate = true, bool|null $force = null) {
    $this->setModel($model);

    if ($this->filesExists($force ? true : false)) {
      self::echoError('At least some of the files exists. If you want to overrite them please use the --force=1 flag');
      $this->valid = false;

      return $this;
    }

    $this->entityValidator = $validate ? true : false;
  }

  public function setModel(string|null $model = null) {
    if (!$model) {
      throw new \Exception('Invalid model supplied in console crud command');
    }

    $validName = Config::validator()
      ->createRegexRules(Config::get('app/validation_rules'))
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

    if ($this->entityValidator) {
      $this->addEntityValidator();
    }

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

    if (!$this->primaryKey) {
      self::echoError('No primary key found');
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

    (new PostFile($this, $this->entityValidator))->buildPost();
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

  private function addEntityValidator() {
    if (!$this->valid) {
      return;
    }

    $validatorFile = new ValidatorFile($this);

    if (!$validatorFile->addValidationMethod()) {
      if ($validatorFile->getError()) {
        self::echoError($validatorFile->getError());
      }

      if ($validatorFile->getWarning()) {
        self::echoWarning($validatorFile->getWarning());
      }

      if ($validatorFile->getMessage()) {
        self::echoInfo($validatorFile->getMessage());
      }
    }
  }
}
