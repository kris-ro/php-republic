<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Signup extends UsersController {

  public function run(): string {
    Config::set('css/signup', 'signup.css');

    return Template::renderView('/front/' . Session::language() . '/users/signup.php', [
      'left_side' => Template::renderView('/front/' . Session::language() . '/homepage/home.php'),
      'errors' => Messages::signupform_error(),
    ]);
  }
}