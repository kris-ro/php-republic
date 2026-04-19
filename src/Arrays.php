<?php

namespace KrisRo\PhpRepublic;

class Arrays {

  /**
   *
   * @param mixed $item
   * @param string $path
   * @param mixed $default
   *
   * @return mixed
   */
  static function getValueByPath($item, $path, $default = null) {
    $path = explode('/', $path);

    if (!is_array($item)) {
      return $default;
    }

    if (!$path) {
      return $default;
    }

    $value = $item;
    foreach ($path as $key) {
      if(!isset($value[$key])) {
        return $default;
      }

      $value = $value[$key];
    }

    return $value;
  }
}
