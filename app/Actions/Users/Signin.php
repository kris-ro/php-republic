<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Signin extends UsersController {

  public function run(): string {
    Config::set('css/signin', 'signup.css');

    return Template::renderView('/front/' . Session::language() . '/users/signin.php', [
      'left_side' => Template::renderView('/front/' . Session::language() . '/homepage/home.php'),
      'errors' => Messages::signinform_error(),
    ]);
  }
}