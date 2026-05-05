<?php

namespace App\Post\Todos;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Files;
use KrisRo\PhpRepublic\Debug;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Update implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'updatetodo';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'todo_id' => Translate::todo('Invalid todo id'),
                'title' => Translate::todo('Invalid title'),
                'details' => Translate::todo('Invalid details'),
                'status' => Translate::todo('Invalid status'),
                'users_id' => Translate::todo('Invalid users id'),
                'created' => Translate::todo('Invalid created'),
                'updated' => Translate::todo('Invalid updated'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Todos\\Update', 'validFormToken']],
                'todo_id' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 4294967295]],
                'title' => ['notEmptyOneLineString', ['maxLength', 255]],
                'details' => ['is_string', ['maxLength', 65535], 'isOptional'],
                'status' => ['is_string', [new Update(), 'validStatus'], 'isOptional'],
                'users_id' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 4294967295], 'isOptional'],
                'created' => ['is_string', ['KrisRo\\PhpRepublic\\Dates', 'isValidMySqlDateTime'], 'isOptional'],
                'updated' => ['is_string', ['KrisRo\\PhpRepublic\\Dates', 'isValidMySqlDateTime'], 'isOptional'],
              ])
              ->processPost();

    if (!$result) {
      Messages::updatetodoform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    $post = Config::validator()->getPost();

    if (empty($post['details'])) {
      $post['details'] = null;
    }
    if (empty($post['status'])) {
      $post['status'] = 'new';
    }
    if (empty($post['users_id'])) {
      $post['users_id'] = null;
    }
    if (empty($post['created'])) {
      $post['created'] = date('Y-m-d H:i:s');
    }
    if (empty($post['updated'])) {
      $post['updated'] = date('Y-m-d H:i:s');
    }

    (new \App\Models\Todo())->updateTodoByTodoId($post);

    Session::set('request/messages/todos/popup_success', Translate::todo('Todo was saved'));

    Request::redirect('/admin/todos');
  }

  public function validStatus($value, $post) {
    $acceptedValues = ['new', 'in progress', 'in review', 'in tests', 'done'];

    if (in_array($value, $acceptedValues)) {
      return true;
    }

    return false;
  }
}


