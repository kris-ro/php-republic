<?php

namespace App\Actions\CrudTests;

use App\Controllers\CrudTests as CrudTestsController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Request;
use App\Models\CrudTest;
use KrisRo\PhpConfig\Config;

class Delete extends CrudTestsController {

  public function run(): string {
    $crudTestId = Request::nth(4);
    if (!(Config::validator() ?? (new Validator()))->positiveInteger($crudTestId) || !($crudTest = (new CrudTest())->getCrudTestByCrudTestId($crudTestId))) {
      Messages::send_popup('Invalid Crud Test Id ID');
      Request::redirect('/admin/crudtests);
    }

    /**
     * This is mapped to public_html/admin/css/crudtests_delete.css
     */
    Config::set('css/crudtests_delete', 'crudtests_delete.css');

    return Template::renderView('/admin/' . Session::language() . '/crudtests/delete.php', [
      'errors' => Messages::deletecrud_testform_error(),
    ]);
  }

}


