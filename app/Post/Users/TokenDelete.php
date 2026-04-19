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
use App\Models\UserToken;

class TokenDelete implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'user-token-delete';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'id' => Translate::users('Invalid Token ID'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\TokenDelete', 'validFormToken']],
                'id' => ['positiveInteger', ['App\\Post\\Users\\TokenDelete', 'validToken']],
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

    if (!(new UserToken())->deleteToken(Config::validator()->getPost()['id'])) {
      trigger_error('User delete failed', E_USER_ERROR);
      Messages::popup_error(Translate::app('User delete failed'));
      return;
    }

    Messages::send_info(Translate::app('Token was deleted'), 'index');
    Request::redirect('/admin/account/user/tokens');
  }

  public static function validToken($value, $post) {
    if (!((new UserToken())->getToken($value))) {
      return false;
    }

    return true;
  }

  public static function validPassword($value, $post) {
    if (!Authenticate::verifyPassword($value ?? '', Session::get('user/password') ?? '')) {
      return false;
    }

    return true;
  }
}
