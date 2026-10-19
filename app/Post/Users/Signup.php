<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;
use App\Models\User as UserModel;

class Signup implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'signup';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'username' => Translate::users('Invalid Name'),
                'role' => Translate::users('Invalid Role'),
                'email' => Translate::users('Invalid Email'),
                'phone' => Translate::users('Invalid Phone'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Signup', 'validFormToken']],
                'username' => ['notEmptyOneLineString'],
                'role' => ['validRole'],
                'email' => [['isValidEmail', 'mode' => \App\Validator::EMAIL_VALIDATOR_REGEXP], ['App\\Post\\Users\\Signup', 'isUnique']],
                'phone' => ['notEmptyOneLineString', 'phone', 'isOptional'],
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

    $post = Config::validator()->getPost();

    if (!($userId = (new UserModel())->setUser($post))) {
      Messages::popup_error(Translate::users('Failed to create the user'));
      return;
    }

    \App\Helpers\Emails::SendNewAccountValidationEmail($userId);

    Session::set('request/messages/index/popup_success', Translate::users('Your account was created. Please follow the instructions set to your email'));
    Request::redirect('/');
  }

  public static function confirmed() {
    if (Request::post('password') != Request::post('repeat')) {
      Config::validator()->setPostValidationMessage('password', Translate::users('Confirmation password does not match'));
      return false;
    }

    return true;
  }

  public static function isUnique($value, $post) {
    return (new UserModel())->getUserByEmail($value) ? false : true;
  } 
}
