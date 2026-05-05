<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Listing;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpConfig\Config;

class Index extends UsersController {

  private $filters = [
    'id' => ['id', []],
    'name' => ['username', []],
    'email' => ['email', []],
    'status' => ['is_active', []],
    'created' => ['created', []],
    'updated' => ['updated', []],
  ];

  public function run(): string {
    /**
     * This is mapped to public_html/admin/css/users_index.css
     */
    Config::set('css/users_index', 'users_index.css');

    $list = new Listing([
      'order-by' => 'id',
      'size' => '10',
      'sort' => 'asc',
    ], $this->filters);

    $list->select(['`users`.*'])
         ->from('users')
         ->filter('users.id', 'id')
         ->filter('users.username', 'name', 'LIKE')
         ->filter('users.email', 'email', 'LIKE')
         ->filter('users.is_active', 'status')
         ->filter('users.created', 'created', '=', 'date')
         ->filter('users.updated', 'updated', '=', 'date');

    $listData = $list->getData();

    $items = [];
    foreach ($listData['data'] as $item) {
      $items[$item['id']] = [
        'created' => Dates::format($item['created']),
        'updated' => Dates::format($item['updated']),
        'is_active' => $item['is_active'] ? 'Yes' : 'No',
      ] + $item;
    }

    return Template::renderView('/admin/' . Session::language() . '/users/index.php', [
      'slim_select' => Request::get('slim_table') ? 'slim-select' : '',
      'items' => $items,
    ]);
  }

}


