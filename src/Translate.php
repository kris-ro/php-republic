<?php

/**
 * Translation class
 *
 * Collects the strings if not already in the translation files
 * Translates the strings
 */

namespace KrisRo\PhpRepublic;

use \KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Resources;

class Translate {

  /**
   * Getter and setter for this class
   *
   * <code>
   * Translate::group('string __PLACE_HOLDER_1__ here ... __PLACE_HOLDER_n__ ', [
   * 		'__PLACE_HOLDER_1__' => 'some value',
   * 		...
   * 		'__PLACE_HOLDER_n__' => 'some nth value',
   * ]);
   * </code>
   * "group" is a name like "users" or "tokens"
   *
   *
   * @param string $model
   * @param array $arguments
   * @return string
   */
  public static function __callStatic(string $model, array $arguments): string {
    $lang = in_array(Session::language(), Config::get('app/languages')) ? Session::language() : Config::get('app/default_language');

    $model = strtolower((string) $model);

    $string = $arguments[0];

    $key = (string) self::normalize($arguments[0]);

    $translation = Config::get('translations/' . $model . '/' . $key);

    if (!$translation) {
      self::addTranslation($model, $key, $string);
      $translation = $string;
    }

    if (isset($arguments[1]) && count($arguments[1])) {
      $keys = [];
			foreach (array_keys($arguments[1]) as $key) {
				$keys[] = '/' . $key . '/';
			}
			return preg_replace($keys, $arguments[1], $translation);
		}

    return $translation;
	}

  /**
	 * Returnes a normalized string.
	 *
	 * @param string   $string   The string to be normalized.
	 * @param mixed    $hash     The hash method to be used or false.
	 *
	 * @return mixed             Normalized string or null otherwise.
	 */
	public static function normalize(string $string, ?string $hash = 'md5') {
		$acceptedHash = ['md5'];

		$patterns = ['/\s+/', '/\v+/', '/ +/'];
		$string = preg_replace($patterns, ' ', strtolower($string));

		$string = trim($string);
		if (empty($string)) {
      return $string;
    }

		if ($hash) {
			$hash = (string) $hash;
			if (!in_array($hash, $acceptedHash)) {
				trigger_error('Invalid hash method.', E_USER_ERROR);
				return false;
			}
			$string = $hash($string);
		}

		return $string;
	}

  /**
   * Saves a new string and reloads the translation files
   *
   * @param type $model
   * @param type $key
   * @param type $string
   */
  private static function addTranslation($model, $key, $string) {
    $defaultLanguage = Config::get('app/default_language');

    $translationsFolder = config::get('app/paths/translations');

    foreach (Config::get('app/languages') as $language) {
      $translationFile = APP_ROOT . DS . $translationsFolder . DS . "{$language}.json";
      $translation = json_decode(file_get_contents($translationFile), true);
      $translation['translations'][$model][$key] = ($defaultLanguage !== $language ? '----' : '') . $string;
      file_put_contents($translationFile, json_encode($translation));
    }

    $bootstrap = Resources::globals('bootstrap');
    $bootstrap->config->loadConfigFile($translationFile);
  }
}