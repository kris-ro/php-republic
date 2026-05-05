<?php

namespace App\Actions\Todos;

use App\Controllers\Todos as TodosController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Request;
use KrisRo\Validator\Validator;
use App\Models\Todo;
use KrisRo\PhpConfig\Config;

class Update extends TodosController {

  public function run(): string {
    $todoId = Request::nth(4);
    if (!(Config::validator() ?? (new Validator()))->positiveInteger($todoId) || !($todo = (new Todo())->getTodoByTodoId($todoId))) {
      Messages::send_popup('Invalid Todo Id ID');
      Request::redirect('/admin/todos');
    }

    /**
     * This is mapped to public_html/admin/css/todos_update.css
     */
    Config::set('css/todos_update', 'todos_update.css');


    return Template::renderView('/admin/' . Session::language() . '/todos/update.php', [
      'item' => $todo,
      'errors' => Messages::updatetodoform_error(),
    ]);
  }

}


