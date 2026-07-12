<?php

namespace KrisRo\PhpRepublic\ConsoleCommands;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;

class Check {

  use ConsoleIO;

  public function __construct() {
    $this->checkPhp();
  }

  private function checkPhp() {
    $check = true;
    foreach (Config::get('php_extensions') as $extention) {
      if (!extension_loaded($extention)) {
        $check = false;
        self::echoWarning("$extention extension is missing!");
      }
    }

    if ($check) {
      self::echoInfo('All extensions are loaded. You\'re set.');
    }
  }
}