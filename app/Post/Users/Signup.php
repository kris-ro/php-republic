<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Signup implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'signup';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'username' => Translate::users('Invalid Name'),
                'email' => Translate::users('Invalid Email'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Signup', 'validFormToken']],
                'username' => ['notEmptyOneLineString'],
                'email' => [['isValidEmail', 'mode' => Validator::EMAIL_VALIDATOR_REGEXP]],
                'password' => [['minLength', 10], 'paranoiaStrongPassword', ['App\\Post\\Users\\Signup', 'confirmed']],
              ])
              ->processPost();

    if (!$result) {
      Messages::signupform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    (new \App\Models\User())->setUser(Config::validator()->getPost());
    Session::set('request/messages/index/popup_success', Translate::users('Your account was created'));
    Request::redirect('/');
  }

  public static function confirmed() {
    if (Request::post('password') != Request::post('repeat')) {
      Config::validator()->setPostValidationMessage('password', Translate::users('Confirmation password does not match'));
      return false;
    }

    return true;
  }
}
