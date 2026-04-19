<?php

/**
 * session handler
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpRepublic\Request;

class Session {

  private static $cronSession = [];

  /**
   * Getter and setter
   *
   * @param string $key
   * @param mixed $args
   * @return mixed
   */
  public static function __callStatic(string $key, $args) {
    if (!empty($args)) {
      return self::set($key, $args);
    } else {
      return self::get($key);
    }
  }

  /**
   * Get a session item
   *
   * @param string $keys
   * @param mixed $return
   * @return mixed
   */
  public static function get(string $keys, $return = null) {
    $item = Request::isCron() ? self::$cronSession : $_SESSION;
    $keys = explode('/', $keys);

    if (empty($keys)) {
      return $return;
    }

    foreach ($keys as $k) {
      if (!empty($item[$k])) {
        $item = $item[$k];
      } else {
        return $return;
      }
    }

    return $item;
  }

  /**
   * Set a session item
   *
   * @param string $keys
   * @param mixed $value
   * @param mixed $return
   * @return mixed
   */
  public static function set($keys = '', $value = null) {
    $keys = explode('/', $keys);

    if (empty($keys)) {
      if (Request::isCron()) {
        self::$cronSession = $value;
      } else {
        $_SESSION = $value;
      }
      return $value;
    }

    if (Request::isCron()) {
      $found = &self::$cronSession;
    } else {
      $found = &$_SESSION;
    }

    foreach ($keys as $key) {
      if (!isset($found[$key]) || !is_array($found[$key])) {
        $found[$key] = [];
      }

      $found = &$found[$key];
    }

    $found = $value;

    return $found;
  }

  public static function addMessage($message) {
    $_SESSION['messages'][] = $message;
  }

  public static function getMessages() {
    if (!isset($_SESSION['messages']) || !is_array($_SESSION['messages'])) {
      return [];
    }

    $messages = array_filter((array) $_SESSION['messages']);

    unset($_SESSION['messages']);

    return $messages;
  }
}
