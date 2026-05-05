<?php

namespace App\Actions\Todos;

use App\Controllers\Todos as TodosController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Add extends TodosController {

  public function run(): string {
    /**
     * This is mapped to public_html/admin/css/todos_add.css
     */
    Config::set('css/todos_add', 'todos_add.css');

    return Template::renderView('/admin/' . Session::language() . '/todos/add.php', [
      'errors' => Messages::addtodoform_error(),
    ]);
  }

}


