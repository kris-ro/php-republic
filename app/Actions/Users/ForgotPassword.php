<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class ForgotPassword extends UsersController {

  public function run(): string {
    Config::set('css/forgot-password', 'signup.css');

    return Template::renderView('/front/' . Session::language() . '/users/forgot_password.php', [
      'left_side' => Template::renderView('/front/' . Session::language() . '/users/_partials/side_prop.php'),
      'errors' => Messages::forgotpasswordform_error(),
    ]);
  }
}