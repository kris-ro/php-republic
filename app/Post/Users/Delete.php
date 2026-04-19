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

class Delete implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'user-delete';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\Delete', 'validFormToken']],
                'password' => [['App\\Post\\Users\\Delete', 'validPassword']],
              ])
              ->processPost();

    if (!$result) {
      Messages::deleteform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    if (!(new \App\Models\User())->deleteUser(Session::get('user/id'))) {
      trigger_error('User delete failed', E_USER_ERROR);
      Messages::popup_error(Translate::app('User delete failed'));
      return;
    }

    Messages::send_info(Translate::app('Have a nice day :)'), 'index');
    Request::redirect('/logout');
  }

  public static function validPassword($value, $post) {
    if (!Authenticate::verifyPassword($value ?? '', Session::get('user/password') ?? '')) {
      return false;
    }

    return true;
  }
}
