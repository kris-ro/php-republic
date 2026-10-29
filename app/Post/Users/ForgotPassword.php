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

class ForgotPassword implements PostDataProcessor {

  use CSRF;

  public static function ValidatePostData(): void {
    self::$formId = 'forgotpassword';

    $self = new ForgotPassword();

    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'email' => Translate::users('Invalid Email'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\ForgotPassword', 'validFormToken']],
                'email' => [['isValidEmail', 'mode' => \App\Validator::EMAIL_VALIDATOR_REGEXP], ['App\\Post\\Users\\ForgotPassword', 'emailExists']],
              ])
              ->processPost();

    if (!$result) {
      Messages::forgotpasswordform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    $userModel = new UserModel();

    $user = $userModel->getUser(Config::validator()->getPost()['email']);

    \App\Helpers\Emails::SendForgotPasswordEmail($user['id']);

    Session::set('request/messages/index/popup_success', Translate::users('Please follow the instructions set to your email'));
    Request::redirect('/');
  }

  public static function emailExists($value, $post) {
    if ((new UserModel())->getUserByEmail($value)) {
      return true;
    }

    return false;
  }
}