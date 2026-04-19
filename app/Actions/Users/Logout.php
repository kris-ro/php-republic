<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Authenticate;

class Logout extends UsersController {

  public function run(): string {
    Authenticate::logout();

//    Session::set('request/messages/index/popup_success', Translate::users('Logout successful'));
    Request::redirect(Config::get('app/default_routes/guest'));
  }
}