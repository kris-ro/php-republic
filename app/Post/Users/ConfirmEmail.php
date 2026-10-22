<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class ConfirmEmail implements PostDataProcessor {

  use CSRF;

  public static function ValidatePostData(): void {
    self::$formId = 'confirmemail';

    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'email' => Translate::users('Invalid Email'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\ConfirmEmail', 'validFormToken']],
                'email' => [['isValidEmail', 'mode' => \App\Validator::EMAIL_VALIDATOR_REGEXP], 'validUserEmail'],
              ])
              ->processPost();

    if (!$result) {
      Messages::confirmemailform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    $post = Config::validator()->getPost();

    $user = (new \App\Models\User())->getUser($post['email']);

    \App\Helpers\Emails::SendNewAccountValidationEmail($user['id']);
  }
}
