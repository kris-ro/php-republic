<?php

namespace App\ConsoleCommands;

use KrisRo\Validator\Validator;
use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use App\ConsoleCommands\Crud\MysqlDataTypeMap;
use App\ConsoleCommands\Crud\ModelFile;
use App\ConsoleCommands\Crud\ControllerFile;
use App\ConsoleCommands\Crud\PostFile;

class Crud {

  use ConsoleIO;

  private $modelName;
  private $controllerName;
  private $fields = [];
  private $unique = [];
  private $autoIncrement = [];

  private $valid = true;

  public function __construct(string|null $model = null) {
    $this->setModel($model);
  }

  public function setModel(string $model) {
    $validName = (new Validator(Config::get('app/validation_rules')))
      ->value($model)
      ->addValidationRule('model_name')
      ->process();

    if (!$validName) {
      self::echoError('Invalid name for a table. It needs to start with a letter, not end with an underscore and contain only letters, numbers and underscore');
      $this->valid = false;
    } else {
      $this->modelName = $model;
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
//    $this->createAction();

    return $this;
  }

  public function createModel() {
    try {
      $model = new MysqlDataTypeMap('crud_test');
      $this->fields = $model->getFieldsData();
//      echo '<pre>';
//      var_dump($this->fields);
//      die(__LINE__ . ' :: ' . __FILE__);
      $this->unique = $model->getUniqueFields();
      $this->autoIncrement = $model->getAutoIncrementFields();

    } catch (\PDOException $e) {
      self::echoError($e->getMessage());
      Config::logger()->error($e->getMessage());

      $this->valid = false;
      return $this;
    }

    try {
      (new ModelFile(
        $this->modelName,
        $this->fields,
        $this->unique,
        $this->autoIncrement)
      )->buildModel();

    } catch(\Exception $e) {
      self::echoError($e->getMessage());
      Config::logger()->error($e->getMessage());

      $this->valid = false;
    }

    return $this;
  }

  public function createController() {
    $this->controllerName = (new ControllerFile($this->modelName))->buildController();
  }

  public function createPost() {
    (new PostFile(
      $this->modelName,
      $this->controllerName,
      $this->fields,
      $this->unique,
      $this->autoIncrement)
    )->buildPost();
  }
}
