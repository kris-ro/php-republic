<?php

namespace KrisRo\PhpRepublic\Traits;

use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Crypto;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;

trait CSRF {

  const INPUT_ELEMENT_NAME = 'csrf_token';

  public static function getFormToken(string $formId): string {
    return '<input type="hidden" name="' . self::INPUT_ELEMENT_NAME . '" value="' . self::setToken($formId) . '">';
  }

  public static function setToken(string $formId): string {
    return Session::set('csrf-' . $formId, Crypto::generateToken());
  }

  public static function getToken(): string {
    return Session::get('csrf-' . self::$formId);
  }

  public static function validFormToken(): bool {
    if (empty(self::$formId)) {
      throw new \RuntimeException('CSRF Missing form ID');
      return '';
    }

    $token = Session::get('csrf-' . self::$formId);
    Session::set('csrf-' . self::$formId, null);
    return ($token && Request::post('csrf_token') === $token) ?: !(Messages::popup_error(Translate::csrf('Invalid Form')));
  }
}
