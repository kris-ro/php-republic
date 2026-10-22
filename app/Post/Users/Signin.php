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
use App\Models\User as UserModel;

class Signin implements PostDataProcessor {

  use CSRF;


  public static function ValidatePostData(): void {
    self::$formId = 'signin';
    
    $self = new Signin();
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'email' => Translate::users('Invalid Email'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Signin', 'validFormToken']],
                'email' => [['isValidEmail', 'mode' => \App\Validator::EMAIL_VALIDATOR_REGEXP]],
                'password' => [['App\\Post\\Users\\Signin', 'validUserAndPassword']],
              ])
              ->processPost();

    if (!$result) {
      Messages::signinform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    $user = (new UserModel())->getUser(Config::validator()->getPost()['email']);

    Session::set('user', $user);
//    Session::set('request/messages/index/popup_success', Translate::users('Signin successful'));
    Request::redirect(Config::get('app/default_routes/' . $user['role']));
  }

  public static function validUserAndPassword($value, $post) {
    $errorMessage = Translate::users('Invalid Login');
    if (!($post['email'] ?? null)) {
      Messages::popup_error($errorMessage);
      return false;
    }

    $userModel = new UserModel();

    $user = $userModel->getuser($post['email']);

    if (!$user) {
      Messages::popup_error($errorMessage);
      return false;
    }

    if (!($user['email_confirmed'] ?? null)) {
      Messages::popup_error(Translate::users('Please validate your email address. Instructions were sent when you created the account'));
      return false;
    }

    if (!($user['is_active'] ?? null)) {
      Messages::popup_error(Translate::users('Your account is not active. If you validated your email address, please contact support'));
      return false;
    }

    if (!Authenticate::verifyPassword($value ?? '', $user['password'] ?? '')) {
      Messages::popup_error($errorMessage);
      return false;
    }

    $userModel->updateLastLogin(['id' => $user['id'], 'last_login' => (new \DateTime('now'))->setTimeZone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s')]);

    return true;
  }
}
