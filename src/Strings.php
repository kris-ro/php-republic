<?php

/**
 * String manipulation utilities
 */

namespace KrisRo\PhpRepublic;

class Strings {

  /**
   * Creates a slug for a string
   *
   * @param string $str
   * @param array $replace
   * @param string $delimiter
   * @return string
   */
  public static function slug(string $str, $replace = [], $delimiter = '-') {
		if (!empty($replace)) {
			$str = str_replace((array) $replace, ' ', $str);
		}

		setlocale(LC_ALL, 'en_US.UTF8');

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

		return $clean;
	}

  public static function toCamelCase($name) {
    return preg_replace_callback(
      '/(_[a-z]{1})/i',
      function ($string) {
        return strtoupper(trim($string[1], '_'));
      },
      $name
    );
  }
}