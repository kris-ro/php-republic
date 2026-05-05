<?php

namespace App\Post\Todos;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Delete implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'deletetodo';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                 self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'todo_id' => Translate::todo('Invalid todo id'),
              ])
              ->addPostValidationRules([
                 self::INPUT_ELEMENT_NAME => [['App\\Post\\Todos\\Delete', 'validFormToken']],
                'todo_id' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 4294967295]],
              ])
              ->processPost();

    if (!$result) {
      Messages::deletetodoform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    (new \App\Models\Todo())->deleteTodoByTodoId(Config::validator()->getPost()['todo_id']);

    Session::set('request/messages/todos/popup_success', Translate::todo('Todo was deleted'));

    Request::redirect('/admin/todos');
  }

}


