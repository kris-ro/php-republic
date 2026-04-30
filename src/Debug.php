<?php

/**
 * Debug
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;

class Debug {

  public static function dump() {
    if (Config::get('_debug') !== true) {
      return;
    }

    echo '<pre>';

    var_dump(...func_get_args());

    $caller = current(debug_backtrace());

    die($caller['file'] . ' :: ' . $caller['line'] . PHP_EOL);
  }

  public static function log($data) {
    if (Config::get('_debug') !== true) {
      return;
    }

    $backtrace = debug_backtrace();
    $caller = current($backtrace);

    file_put_contents(Config::get('app/paths/logs') . '/test.txt', $caller['file'] . ' :: ' . $caller['line'] . PHP_EOL, FILE_APPEND);
    file_put_contents(Config::get('app/paths/logs') . '/test.txt', print_r(func_get_args(), true) . PHP_EOL, FILE_APPEND);
    // file_put_contents(Config::get('app/paths/logs') . '/test.txt', print_r($backtrace, true) . PHP_EOL, FILE_APPEND);
  }
}