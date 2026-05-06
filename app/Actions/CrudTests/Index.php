<?php

namespace App\Actions\CrudTests;

use App\Controllers\CrudTests as CrudTestsController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Listing;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpConfig\Config;

class Index extends CrudTestsController {

  private $filters = [
    'crud_test_id' => ['crud_test_id', []],
    'email' => ['email', []],
    'uuid_field' => ['uuid_field', []],
  ];

  public function run(): string {
    /**
     * This is mapped to public_html/admin/css/crudtests_index.css
     */
    Config::set('css/crudtests_index', 'crudtests_index.css');

    $list = new Listing([
      'order-by' => 'id',
      'size' => '10',
      'sort' => 'asc',
    ], $this->filters);

    $list->select(['`crud_test`.*'])
         ->from('crud_test')
         ->filter('crud_test.crud_test_id', 'crud_test_id')
         ->filter('crud_test.email', 'email', 'LIKE')
         ->filter('crud_test.uuid_field', 'uuid_field');

    $listData = $list->getData();

    $items = [];
    foreach ($listData['data'] as $item) {
      $items[$item['crud_test_id']] = [
        'timestamp_time' => Dates::format($item['timestamp_time']),
        'date_time_field' => Dates::format($item['date_time_field']),
        'date_field' => Dates::format($item['date_field']),
        'boolean_field' => $item['boolean_field'] ? 'Yes' : 'No',
        'uuid_field' => bin2hex($item['uuid_field']),
        'time_field' => Dates::format($item['time_field'], 'H:i:s'),
      ] + $item;
    }

    return Template::renderView('/admin/' . Session::language() . '/crudtests/index.php', [
      'slim_select' => Request::get('slim_table') ? 'slim-select' : '',
      'items' => $items,
    ]);
  }

}


