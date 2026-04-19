<?php

/**
 * Wrapper for <code>$GLOBALS</code>
 */

namespace KrisRo\PhpRepublic;

class Resources {

  public static function __callStatic($name, $arguments) {
    return self::get($arguments[0], $name);
  }

  private static function get($key, $name) {
    $item = null;

    if (!is_string($key)) {
      return $item;
    }

    switch ($name) {
      case 'globals':
        if (isset($GLOBALS[$key])) {
          $item = $GLOBALS[$key];
        } elseif (class_exists(ucfirst($key))) {
          $className = ucfirst($key);
          $GLOBALS[$key] = new $className();
          $item = $GLOBALS[$key];
        }
    }

    return $item;
  }
}
