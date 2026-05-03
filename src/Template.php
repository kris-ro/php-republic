<?php

/**
 * Template processing class
 */

namespace KrisRo\PhpRepublic;

use \KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Resources;
use KrisRo\PhpRepublic\Traits\CSRF;

class Template {

  use CSRF;

  public const BR = '<br>';

  public const HIDDEN_FOOTER = '<!-- footer hidden -->';
  public const HIDDEN_TOP_MENU = '<!-- top menu hidden -->';
  public const HIDDEN_LEFT_SIDE_BAR = '<!-- left side bar hidden -->';
  public const HIDDEN_RIGHT_SIDE_BAR = '<!-- right side bar hidden -->';

  private static $format = 'screen';
  private static $data = [];

  /**
   * Setter and getter for this class
   *
   * @param string $key
   * @param array $arguments
   *
   * @return mixed
   */
  public static function __callStatic($key, $arguments) {
    if (isset($arguments[0]) && !isset($arguments[1])) {
      return self::set($key, $arguments[0]);
    } else {
      return self::get($key, $arguments[0] ?? '', $arguments[1] ?? true, $arguments[2] ?? false);
    }
  }

  /**
   * Setter for this class property
   *
   * @param string $key
   * @param mixed $data
   * @return void
   */
  public static function set(string $key, $data) {
    $key = explode('/', $key);

    if (empty($key)) {
      return;
    }

    $key[0] = strtoupper($key[0]);

    $found = &self::$data;

    foreach ($key as $k) {
      if (!isset($found[$k]) || !is_array($found[$k])) {
        $found[$k] = [];
      }

      $found = &$found[$k];
    }

    $found = $data;
  }

  /**
   * Getter for this class property
   *
   * @param string $key
   * @param bool|null $clear
   * @param string|null $return
   * @return string|null
   */
  public static function get(string $key, ?string $return = '', ?bool $escape = true, ?bool $clear = null) {
    $key = explode('/', $key);

    if (empty($key)) {
      return $return;
    }

    $key[0] = strtoupper($key[0]);

    $item = self::$data;

    foreach ($key as $k) {
      if (!empty($item[$k])) {
        $item = $item[$k];
      } else {
        return $return;
      }
    }

    if ($clear) {
      // die(__LINE__ . ' :: ' . __FILE__);
      unset(self::$data[current($key)]);
      self::clearRequestMessages(current($key));
    }

    return $escape ? self::escape($item) : $item;
  }

  /**
   * Renders a view
   *
   * @param string $viewPath
   * @param array|null $data
   * @param string $format
   * @return string
   */
  public static function renderView(string $viewPath, $data = null, $format = 'screen'): string {
    if (SECTION == 'api') {
      return self::renderApi($data);
    }

    $path = APP_ROOT . DS . Config::get('app/paths/views') . $viewPath;

    if (empty($viewPath) || !file_exists($path)) {
      return '';
    }

    // saving previous path
    $dataPath = self::get('viewDataPath');

    $pathDetails = pathinfo($path);
    self::set('viewDataPath', 'viewData/' . trim($pathDetails['dirname'], '/') . '/' . trim($pathDetails['filename'], '/'));

    self::set(self::get('viewDataPath'), $data);
    $data = null;

    self::$format = in_array($format, ['print', 'popup']) ? $format : 'screen';

    ob_start();
    include($path);

    // restoring previous path
    self::set('viewDataPath', $dataPath);

    return ob_get_clean();
  }

  /**
   * Escapes data for template rendering
   *
   * @param string $item
   * @param bool|null $escape
   * @return mixed
   */
  private static function view(string $item, ?bool $escape = true, mixed $default = null) {
    $data = self::get(self::get('viewDataPath') . '/' . $item, '', $escape) ?: $default;

    return $escape ? self::escape($data) : $data;
  }

  /**
   * Format data for API requests
   *
   * @param array|null $data
   * @return mixed
   */
  public static function renderApi($data = null) {
    $format = array_flip(Config::get('app/api_formats'))[Config::get('app/requestFormat')];

    switch ($format) {
      case 'json':
        return self::formatJson($data);
      case 'xml':
        return self::formatXml($data);
    }
  }

  /**
   * Format data to JSON for API requests
   *
   * @param array|null $data
   * @return string
   */
  private static function formatJson($data) {
    $template = Config::get('app/default_api_respons_format');

    $template['error'] = Messages::getAllMessages(Messages::ERROR);
    $template['messages'] = Messages::getAllMessages();
    $template['metadata']['timestamp'] = time();

    if (empty($template['error'])) {
      $template['success'] = true;
      $template['data'] = $data;
    }

    return json_encode($template);
  }

  private static function formatXml($data) {
    $template = Config::get('app/default_api_respons_format');

    $template['error'] = Messages::getAllMessages(Messages::ERROR);
    $template['messages'] = Messages::getAllMessages();
    $template['metadata']['timestamp'] = time();

    if (empty($template['error'])) {
      $template['success'] = true;
      $template['data'] = $data;
    }

    $xml = new \SimpleXMLElement('<root/>');
    self::buildXmlStructure($template, $xml);

    return $xml->asXML();
  }

  private static function buildXmlStructure($data, &$xmlData) {
    foreach ($data as $key => $value) {
      if (is_numeric($key)) {
        $key = 'item ' . $key; // XML doesn't allow numeric keys
      }

      if (is_array($value)) {
        $subnode = $xmlData->addChild($key);
        self::buildXmlStructure($value, $subnode);

      } else {
        $xmlData->addChild((string) $key, htmlspecialchars((string) $value));
      }
    }
  }

  /**
   * Loads main layout
   *
   * @return string
   */
  public static function layout() {
    return self::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . DS . 'layout.php');
  }

  /**
   * Loads pagination template
   *
   * @return string
   */
  public static function paginationTemplate() {
    return self::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . DS . 'pagination.php');
  }

  /**
   * TRUE if format matches
   *
   * @param string $format
   * @return bool
   */
  public static function format(string $format) {
    if ($format == self::$format) {
      return true;
    }
  }

  /**
   * Load a html template file
   *
   * @param string $path
   * @param mixed $data
   * @return string
   */
  public static function load(string $path, $data = null) {
    if (!file_exists($path)) {
      return '';
    }

//    ob_start();
//    debug_print_backtrace();
//    $debug = ob_get_clean();
//    file_put_contents('/var/www/html/log.txt', print_r($debug, true) . PHP_EOL, FILE_APPEND);

    ob_start();
    include("{$path}");
    $content = ob_get_clean();

    return $content;
  }

  /**
   * Removes messages sent via <code>$)GET</code>
   *
   * @param type $key
   * @return type
   */
  private static function clearRequestMessages($key) {
    $request = Resources::globals('request');
    if (!$request) {
      return;
    }

    $request->clearMessages(str_replace('_messages', '', $key));
  }

  /**
   * Returns CSS class if menu tree is open
   *
   * @param string $path
   * @return string
   */
  public static function openTreeMenu(string $path): string {
    // Debug::log($path, Config::current_page());
    return strpos(Config::current_page(), $path) === 0 ? 'menu-open' : '';
  }

  /**
   * Returns CSS class if menu is active
   *
   * @param array $paths
   * @return string
   */
  public static function isActiveMenu(array $paths): string {
    return in_array(Config::current_page(), $paths) ? 'active' : '';
  }

  private static function escape(mixed $item) {
    if (is_string($item)) {
      return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    }

    if (is_array($item)) {
      foreach ($item as $key => $value) {
        $item[$key] = self::escape($value);
      }
    }

    return $item;
  }
}
