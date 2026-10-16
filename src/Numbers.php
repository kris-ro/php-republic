<?php

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;

class Numbers {

  public static function format (int|float $number, int|null $decimals = 2, string|null $language = null) {
    if (!$language || !in_array($language, Config::get('app/languages'))) {
      $language = Config::get('app/default_language');
    }

    return number_format($number, $decimals, Config::get("app/{$language}/decimals"), Config::get("app/{$language}/thousand"));
  }
}