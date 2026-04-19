<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Delete extends UsersController {

  public function run(): string {
    Config::set('css/delete', 'profile.css');

    return Template::renderView(DS . SECTION . DS . Session::language() . '/users/delete.php', [
      'left_user_summary' => Template::renderView(DS . SECTION . DS . Session::language() . '/users/_partials/user_summary.php'),
      'errors' => Messages::deleteform_error(),
    ]);
  }
}