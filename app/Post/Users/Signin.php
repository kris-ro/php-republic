<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Authenticate;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;
use App\Models\User as UserModel;

class Signin implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'signin';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'email' => Translate::users('Invalid Email'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Signin', 'validFormToken']],
                'email' => [['isValidEmail', 'mode' => Validator::EMAIL_VALIDATOR_REGEXP]],
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

    $userModel->updateLastLogin(['id' => $user['id'], 'last_login' => (new \DateTime('now'))->setTimeZone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s')]);

    if (!$user || !Authenticate::verifyPassword($value, $user['password'] ?? '')) {
      Messages::popup_error($errorMessage);
      return false;
    }

    return true;
  }
}
