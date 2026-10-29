<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpConfig\Config;
use App\Models\RecoveryToken as RecoveryTokenModel;

class ForgotPasswordReset extends UsersController {

  public function run(): string {
    Config::set('css/forgot-password-reset', 'signup.css');

    if (!($encodedToken = Request::nth(2))) {
      Request::redirect('/');
    }
// \KrisRo\PhpRepublic\Debug::dump($encodedToken, (new RecoveryTokenModel())->decodeToken($encodedToken));
//     if (!($token = (new RecoveryTokenModel())->decodeToken($encodedToken))) {
//       Request::redirect('/');
//     }

    return Template::renderView('/front/' . Session::language() . '/users/forgot_password_reset.php', [
      'left_side' => Template::renderView('/front/' . Session::language() . '/users/_partials/side_prop.php'),
      'token' => $encodedToken,
      'errors' => Messages::forgotpasswordresetform_error(),
    ]);
  }
}