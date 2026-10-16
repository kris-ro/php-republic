<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use App\Models\User as UserModel;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class ValidateEmail extends UsersController {

  public function run(): string {
    if (!Request::isPost() && ($code = Request::param('email', ['email']))) {
      if ((new UserModel())->validateEmailAddress($code)) {
        Session::set('request/messages/signin/popup_success', Translate::users('Your email address is now validated. You can signin'));
        Request::redirect('/signin');
      }

      Messages::popup_error(Translate::users('Failed to validate your email address'));
    }

    Config::set('css/users/confirm/email', 'signup.css');

    return Template::renderView('/front/' . Session::language() . '/users/confirm_email.php', [
      'left_side' => Template::renderView('/front/' . Session::language() . '/homepage/home.php'),
      'errors' => Messages::confirmemailform_error(),
    ]);
  }
}