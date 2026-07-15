<?php

/**
 * Authentication related functionality
 * - hash passwords
 * - verify hashed password
 * - created and destroys user session
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\ApiTokens;
use KrisRo\PhpConfig\Config;

class Authenticate {

  private static $hashType = PASSWORD_DEFAULT;

  /**
   * Create a password hash
   *
   * @param string $password
   * @return string
   */
  public static function hashPassword(string $password): string {
    return password_hash($password, self::$hashType);
  }

  /**
   * Validates a password against a hash
   *
   * @param string $password
   * @param string $passwordHash
   * @return string
   */
  public static function verifyPassword(string $password, string $passwordHash): string {
    return password_verify($password, $passwordHash);
  }

  /**
   * Unset user session on logout
   */
  public static function logout() {
    Session::set('user', null);
  }

  /**
   * Creates user session on API requests
   *
   * @return type
   */
  public static function createSessionFromApiToken() {
    if (SECTION !== 'api') {
      return;
    }

    if (!Request::header('api-token') || !is_string(Request::header('api-token'))) {
      return;
    }

    $_SESSION = [];

    $message = Translate::users('Invalid user');

    $apiTokens = (new ApiTokens())->setEncryptionKey(Config::get('crypto/key'));

    if (!($token = $apiTokens->decryptToken(Request::header('api-token')))) {
      Config::logger()->info('Invalid api token used');
      Messages::popup_error($message);
      return;
    }

    if (empty($token['uid']) || !Config::validator()->positiveInteger($token['uid'])) {
      Config::logger()->info('Invalid api token used');
      Messages::popup_error($message);
      return;
    }

    if (!($user = (new \App\Models\User())->getUser($token['uid']))) {
      Config::logger()->info('Invalid api token used');
      Messages::popup_error($message);
      return;
    }

    if (!(new \App\Models\UserToken())->getTokenByFingerprint(Request::header('api-token'), $token['uid'])) {
      Config::logger()->info('Invalid api token used');
      Messages::popup_error($message);
      return;
    }

    Session::set('user', $user);
  }
}