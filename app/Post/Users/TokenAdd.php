<?php

namespace App\Post\Users;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\ApiTokens;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;
use App\Models\UserToken;

class TokenAdd implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'user-token-add';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'label' => Translate::tokens('Invalid Label'),
                'expire' => Translate::users('Invalid Expiration Interval'),
                'password' => Translate::users('Invalid Password'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\Users\\TokenAdd', 'validFormToken']],
                'label' => ['notEmptyOneLineString', ['App\\Post\\Users\\TokenAdd', 'uniqueLabel']],
                'expire' => ['notEmptyOneLineString', ['App\\Post\\Users\\TokenAdd', 'validExpirationInterval']],
                'password' => ['notEmptyOneLineString', ['App\\Post\\Users\\TokenAdd', 'validPassword']],
              ])
              ->processPost();

    if (!$result) {
      Messages::tokenaddform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    $expirationDate = ApiTokens::expirationIntervals(null, Config::validator()->getPost()['expire']);

    $apiTokens = (new ApiTokens())->setEncryptionKey(Config::get('crypto/key'));
    $token = $apiTokens->createToken(Session::get('user/id'), $expirationDate->getTimestamp());

    $saved = (new UserToken())->setToken([
      'label' => Config::validator()->getPost()['label'],
      'user_id' => Session::get('user/id'),
      'fingerprint' => hash('sha256', $token),
      'random_nonce' => $apiTokens->getNonce(),
      'expires' => $expirationDate->format('Y-m-d H:i:s'),
    ]);

    if (!$saved) {
      trigger_error('Token add failed', E_USER_ERROR);
      Messages::popup_error(Translate::app('Token add failed'));
    }

    Template::user_token($token);
  }

  public static function validPassword($value, $post) {
    return Authenticate::verifyPassword($value ?? '', Session::user()['password']);
  }

  public static function validExpirationInterval($value, $post) {
    $expirationIntervals = ApiTokens::expirationIntervals();
    return isset($expirationIntervals[$value]);
  }

  public static function uniqueLabel($value, $post) {
    if ((new UserToken())->getTokenByLabel($value)) {
      Config::validator()->setPostValidationMessage('label', Translate::tokens('You already have token with this label'));
      return false;
    }

    return true;
  }
}
