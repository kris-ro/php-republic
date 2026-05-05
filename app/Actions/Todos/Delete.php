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

class Delete extends TodosController {

  public function run(): string {
    $todoId = Request::nth(4);
    if (!(Config::validator() ?? (new Validator()))->positiveInteger($todoId) || !($todo = (new Todo())->getTodoByTodoId($todoId))) {
      Messages::send_popup('Invalid Todo Id ID');
      Request::redirect('/admin/todos');
    }

    /**
     * This is mapped to public_html/admin/css/todos_delete.css
     */
    Config::set('css/todos_delete', 'todos_delete.css');


    return Template::renderView('/admin/' . Session::language() . '/todos/delete.php', [
      'item' => $todo,
      'errors' => Messages::deletetodoform_error(),
    ]);
  }

}


