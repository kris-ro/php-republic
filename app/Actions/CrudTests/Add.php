<?php

namespace App\Post\CrudTests;

use App\Controllers\CrudTests as CrudTestsController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Add extends CrudTestsController {

  public function run(): string {
    /**
     * This is mapped to public_html/admin/css/crudtests_add.css
     */
    Config::set('css/crudtests_add', 'crudtests_add.css');

    return Template::renderView('/admin/' . Session::language() . '/crudtests/add.php', [
      'errors' => Messages::addcrud_testform_error(),
    ]);
  }

}


