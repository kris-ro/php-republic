<?php

namespace App\Actions\Todos;

use App\Controllers\Todos as TodosController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Listing;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpConfig\Config;

class Index extends TodosController {

  private $filters = [
    'todo_id' => ['todo_id', []],
    'title' => ['title', []],
    'users_id' => ['users_id', []],
    'created' => ['created', []],
    'updated' => ['updated', []],
  ];

  public function run(): string {
    /**
     * This is mapped to public_html/admin/css/todos_index.css
     */
    Config::set('css/todos_index', 'todos_index.css');

    $list = new Listing([
      'order-by' => 'id',
      'size' => '10',
      'sort' => 'asc',
    ], $this->filters);

    $list->select(['`todo`.*'])
         ->from('todo')
         ->filter('todo.todo_id', 'todo_id')
         ->filter('todo.title', 'title', 'LIKE')
         ->filter('todo.users_id', 'users_id')
         ->filter('todo.created', 'created', '=', 'date')
         ->filter('todo.updated', 'updated', '=', 'date');

    $listData = $list->getData();

    $items = [];
    foreach ($listData['data'] as $item) {
      $items[$item['todo_id']] = [
        'created' => Dates::format($item['created']),
        'updated' => Dates::format($item['updated']),
      ] + $item;
    }

    return Template::renderView('/admin/' . Session::language() . '/todos/index.php', [
      'slim_select' => Request::get('slim_table') ? 'slim-select' : '',
      'items' => $items,
    ]);
  }

}


