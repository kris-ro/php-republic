<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;
use App\Models\User as UserModel;
use App\Models\RecoveryToken as RecoveryTokenModel;

class ForgotPasswordReset implements PostDataProcessor {

  use CSRF;

  public static function ValidatePostData(): void {
    self::$formId = 'forgotpasswordreset';

    $self = new ForgotPasswordReset();

    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'password' => Translate::users('Invalid password'),
                'repeat' => Translate::users('Confirmation password does not match'),
                'token' => Translate::users('Invalid token'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\ForgotPasswordReset', 'validFormToken']],
                'password' => [['minLength', 10], 'paranoiaStrongPassword'],
                'repeat' => [['App\\Post\\Users\\ForgotPasswordReset', 'confirmed']],
                'token' => [['App\\Post\\Users\\ForgotPasswordReset', 'tokenValidation']],
              ])
              ->processPost();

    if (!$result) {
      Messages::forgotpasswordresetform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    $errors = Config::validator()->getPostValidationMessages();

    // redirect to homepage if no other errors are present, token is invalid
    if (!empty($errors['token']) && count($errors) == 1) {
      Session::set('request/messages/index/' . Template::POPUP_ERROR, Translate::users('Invalid request'));
      Request::redirect('/');
    }

    if (!empty($errors)) {
      return;
    }

    $post = Config::validator()->getPost();

    $token = (new RecoveryTokenModel())->decodeToken($post['token']);

    $userData = [
      'password' => $post['password'],
      'id' => $token['uid'],
    ];

    if ((new UserModel())->updateUser($userData)) {
      Session::set('request/messages/signin/' . Template::POPUP_SUCCESS, Translate::users('Password was changed'));
      Request::redirect('/signin');
    }

    Session::set('request/messages/forgot-password-reset/' . Template::POPUP_ERROR, Translate::users('Failed to update password'));
    Request::redirect('/forgot-password-reset');
  }

  public static function confirmed($value, $post) {
    if ($value == Request::post('password')) {
      return true;
    }

    return false;
  }

  public static function tokenValidation($value, $post) {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return false;
    }

    if (!($token = Request::post('token'))) {
      return false;
    }

    if (!($decodedToken = (new RecoveryTokenModel())->decodeToken($token))) {
      return false;
    }

    if ($decodedToken['uid'] ?? null) {
      return true;
    }

    return false;
  }
}