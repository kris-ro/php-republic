<?php

namespace App\Post\CrudTests;

use App\Controllers\CrudTests as CrudTestsController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Request;
use App\Models\CrudTest;
use KrisRo\PhpConfig\Config;

class Update extends CrudTestsController {

  public function run(): string {
    $crudTestId = Request::nth(4);
    if (!(Config::validator() ?? (new Validator()))->positiveInteger($crudTestId) || !($crudTest = (new CrudTest())->getCrudTestByCrudTestId($crudTestId))) {
      Messages::send_popup('Invalid Crud Test Id ID');
      Request::redirect('/admin/crudtests);
    }

    /**
     * This is mapped to public_html/admin/css/crudtests_update.css
     */
    Config::set('css/crudtests_update', 'crudtests_update.css');

    return Template::renderView('/admin/' . Session::language() . '/crudtests/update.php', [
      'errors' => Messages::updatecrud_testform_error(),
    ]);
  }

}


