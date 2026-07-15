<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Profile implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'profile';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'username' => Translate::users('Invalid Name'),
                'email' => Translate::users('Invalid Email'),
                'password' => Translate::users('Invalid Password'),
                'current_password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Profile', 'validFormToken']],
                'username' => ['notEmptyOneLineString'],
                'email' => [['isValidEmail', 'mode' => \App\Validator::EMAIL_VALIDATOR_REGEXP]],
                'password' => ['isOptional', ['minLength', 10], 'paranoiaStrongPassword', ['App\\Post\\Users\\Profile', 'confirmed']],
                'current_password' => ['notEmptyOneLineString', ['App\\Post\\Users\\Profile', 'validPassword']],
              ])
              ->processPost();

    if (!$result) {
      Messages::profileform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    if ((new \App\Models\User())->updateUser(['id' => Session::user()['id']] + Config::validator()->getPost())) {
      Session::set('user', (new \App\Models\User())->getUser(Session::user()['id']));
      Session::set('request/messages/profile/popup_success', Translate::users('Your account was updated'));
    }
  }

  public static function validPassword() {
    return Authenticate::verifyPassword(Request::post('current_password'), Session::user()['password']);
  }

  public static function confirmed() {
    if (Request::post('password') != Request::post('repeat')) {
      Config::validator()->setPostValidationMessage('password', Translate::users('Confirmation password does not match'));
      return false;
    }

    return true;
  }
}
