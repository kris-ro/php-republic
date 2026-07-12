<?php

namespace KrisRo\PhpRepublic;

use \KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Arrays;
use KrisRo\PhpRepublic\Messages;

class Request {

  private $error = [];
  private $warning = [];
  private $info = [];
  private $success = [];
  private $post = null;
  private static $blockPost = false;
  private static $headers = null;

  const TYPE_ERROR = 'error';
  const TYPE_WARNING = 'warning';
  const TYPE_INFO = 'info';
  const TYPE_SUCCESS = 'success';

  /**
   * Setting requested action
   * Imports messages from past requests
   */
  public function __construct() {
    $this->getCurrentPage();
    $this->applicationJsonToPost();
    $this->getSessionMessages();
  }

  /**
   * Validate and process POST requests
   *
   * @return void
   */
  public function processPost(): void {
    if (!$this->isPost()) {
      return;
    }

    $postProcessors = Config::get('post/' . SECTION);
    if (!$postProcessors) {
      return;
    }

    $processor = isset($postProcessors[Config::get('current_page')]) ? $postProcessors[config::get('current_page')] : false;
    if (!$processor) {
      return;
    }

    if (!class_exists($processor) || !in_array('KrisRo\\PhpRepublic\\Interfaces\\PostDataProcessor', class_implements($processor))) {
      return;
    }

    $validatorClass = Config::get('app/validator');
    Config::set('validator', new $validatorClass());

    $processor::ValidatePostData();
    $processor::ProcessPostData();

    return;
  }

  /**
   * TRUE if request is to be treated as POST request
   *
   * @return bool
   */
  public static function isPost(): bool {
    if (self::$blockPost) {
      return false;
    }

    if ((strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')) {
      return false;
    }

    return true;
  }

  /**
   * TRUE if is a command from text interface
   *
   * @return bool
   */
  public static function isCron(): bool {
    return php_sapi_name() == 'cli' ? true : false;
  }

  /**
   * Execute the requested action and populate Template variables
   */
  public function run() {
    $routes = Config::get('routes');

    if ($routes[SECTION] ?? null) {
      $class = $routes[SECTION][Config::get('current_page')];
    } else {
      self::redirect();
    }

    $action = new $class();
    if (!($action instanceof \KrisRo\PhpRepublic\Controller)) {
      trigger_error('Action must extend \KrisRo\PhpRepublic\Controller', E_USER_ERROR);
    }

    Template::page($action->run());

    $this->overrideLayoutComponents();

    $this->loadPopups();

    if (!in_array(SECTION, Config::get('app/json_sections'))) {
      if (empty(Template::topMenu())) {
        Template::topMenu(Template::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . '/' . 'top_menu.php', ['languages' => array_diff(Config::get('app/languages'), [Session::language()])]));
      }

      if (empty(Template::sideMenu())) {
        Template::sideMenu(Template::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . '/' . 'side_menu.php', ['languages' => array_diff(Config::get('app/languages'), [Session::language()])]));
      }

      if (empty(Template::rightBar())) {
        Template::rightBar(Template::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . '/' . 'right_bar.php', ['languages' => array_diff(Config::get('app/languages'), [Session::language()])]));
      }

      if (empty(Template::footer())) {
        Template::footer(Template::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . '/' . 'footer.php', ['languages' => array_diff(Config::get('app/languages'), [Session::language()])]));
      }

      Template::js(Template::load(APP_ROOT . DS . Config::get('app/paths/language_templates') . '/' . 'js.php', ['languages' => array_diff(Config::get('app/languages'), [Session::language()])]));
    }
  }

  /**
   * Load popups if HTML is served or structured JSON with messages otherwise
   *
   * @return void
   */
  private function loadPopups(): void {
    $templates = Config::get('app/paths/language_templates');

    $asArray = in_array(SECTION, Config::get('app/json_sections')) ? true : false;

    if (Messages::popup_error()) {
      Template::popup_error(Template::load(APP_ROOT . DS . $templates . '/popup_error.php', $asArray ? Messages::popup_error() : implode(Template::BR, Messages::popup_error())));
    }

    if (Messages::popup_warning()) {
      Template::popup_warning(Template::load(APP_ROOT . DS . $templates . '/popup_warning.php', $asArray ? Messages::popup_warning() : implode(Template::BR, Messages::popup_warning())));
    }

    if (Messages::popup_info()) {
      Template::popup_info(Template::load(APP_ROOT . DS . $templates . '/popup_info.php', $asArray ? Messages::popup_info() : implode(Template::BR, Messages::popup_info())));
    }

    if (Messages::popup_success()) {
      Template::popup_success(Template::load(APP_ROOT . DS . $templates . '/popup_success.php', $asArray ? Messages::popup_success() : implode(Template::BR, Messages::popup_success())));
    }
  }

  /**
   * Override or hide top menu, side menu, footer 
   * or any other layout components
   */
  private function overrideLayoutComponents(): void {
    if (Request::get('slim_table') || Request::header('slim-table') || Template::format('popup')) {
      if (empty(Template::topMenu())) {
        Template::topMenu(Template::HIDDEN_TOP_MENU);
      }
  
      if (empty(Template::sideMenu())) {
        Template::sideMenu(Template::HIDDEN_LEFT_SIDE_BAR);
      }
  
      if (empty(Template::rightBar())) {
        Template::rightBar(Template::HIDDEN_RIGHT_SIDE_BAR);
      }
  
      if (empty(Template::footer())) {
        Template::footer(Template::HIDDEN_FOOTER);
      }
    }
  }

  /**
   * Get value for a <code>$_POST</code> entry
   *
   * @param string $index
   * @param mixt $default
   * @return mixt
   */
  public static function post(string $index, $default = null){
    return Arrays::getValueByPath($_POST, $index, $default);
	}

  /**
   * Get a header value
   *
   * @param string $index
   * @param mixt $default
   * @return mixed
   */
  public static function header(string $index, $default = null){
    return Arrays::getValueByPath(static::getHeaders(), $index, $default);
	}

  public function setContentTypeHeader() {
    header('Content-Type: ' . Config::get('app/requestFormat'));
  }

  /**
   * Get value for a <code>$_GET</code> entry
   *
   * @param string $index
   * @param mixt $default
   * @return mixt
   */
	public static function get($index, $default = null){
    return Arrays::getValueByPath($_GET, $index, $default);
	}

  /**
   * Get value for a friendly URL param
   *
   * @param type $searchKey
   * @param type $searchParams
   * @param type $default
   * @return null
   */
  public static function param($searchKey = '', $searchParams = [], $default = null) {
    if (!$searchKey) {
      return $default;
    }

    $components = Config::get('url/components');
    $pageParams = Config::get('app/pagination/url_params');

    $item = '';
    $position = 0;

    if (!is_array($components ?? null)) {
      return $default;
    }

    foreach ($components as $index => $component) {
      if ($component == $searchKey) {
        $position = $index;
        $item = $component;
        break;
      }
    }

    if (empty($item)) {
      return $default;
    }

    if (in_array($searchKey, $pageParams)) {
      return str_replace("{$searchKey}-", '', $item);
    }

    if (in_array($searchKey, $searchParams)) {
      if (isset($components[++$position])) {
        if (count($searchParams) == 1) {
          return urldecode($components[$position]);

        } else {
          $values = [];
          $keys = array_merge($searchParams, $pageParams);

          while (($components[$position] ?? null) && !in_array($components[$position], $keys)) {
            $values[] = urldecode($components[$position++]);
          }

          return $values;
        }
      } else {
        return $default;
      }
    }

    return urldecode($item);
  }

  /**
   * Set current page by matching request URL against the routing in config
   *
   * @return void
   */
  private function getCurrentPage(): void {
    $pages = Config::get('routes/' . SECTION, []);

    $pageCandidate = '';
    foreach (array_keys($pages) as $key) {
      if (!preg_match('%/' . $key . '%i', Config::get('url/uri'))) {
        continue;
      }

      if (!$pageCandidate) {
        $pageCandidate = $key;
        continue;
      }

      if (strlen($key) > strlen($pageCandidate)) {
        $pageCandidate = $key;
      }
    }

    self::$blockPost = $pageCandidate ? false : true;

    Config::set('current_page', $pageCandidate ?: 'index');
  }

  /**
   * Populates <code>$_POST<code> with json data
   * in context of POST requests with JSON
   *
   * @return void
   */
  private function applicationJsonToPost(): void {
    if (!self::isPost() || strtolower(self::getHeaders()['Content-Type'] ?? '') != 'application/json') {
      return;
    }

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return;
    }

    $_POST = $data;
  }

  /**
   * Reads all readers into <code>$headers</code> property
   * @return null|array
   */
  private static function getHeaders(): null|array {
    if (self::$headers === null) {
      self::$headers = getallheaders();
    }

    return self::$headers;
  }

  /**
   * Redirects to a specific URL and exits
   *
   * @param string|null $address
   * @param array|null $getParams
   * @return void
   */
  public static function redirect(?string $address = '/', ?array $getParams = []): void {
    header('Location: ' . $address . ($getParams ? '?' . (function () use ($getParams) {
      $values = [];

      foreach ($getParams as $key => $value) {
        if (!is_string($value)) {
          continue;
        }

        $values[] = $key . '=' . urlencode($value);
      }

      return $values ? implode('&', $values) : '';
    })() : ''));
    exit;
  }

  /**
   * Extracts the nth param from the friendly URL string
   *
   * @param int $nth
   * @return string|false|null
   */
  public static function nth(int $nth) {
    $components = Config::get('url/components');
    $offset = max(0, $nth - 1);
    return current(array_slice($components, $offset, 1));
  }

  /**
   * Build http(s) URL
   *
   * @param type $path
   * @param type $port
   * @return type
   */
  public static function buildUrl($path = '', $port = '') {
    return Config::get('url/protocol') . '://' . Config::get('url/domain') . ($port ? ":{$port}" : '') . '/' . trim($path, '/ ');
  }

  /**
   * Transfers past request's messages from <code>\KrisRo\PhpRepublic\Session<\code> to <code>\KrisRo\PhpRepublic\Messages<\code>
   *
   * @return void
   */
  private function getSessionMessages(): void {
    $messages = Session::get('request/messages', []);

    foreach (($messages[Config::current_page()] ?? []) as $type => $messages) {
      Messages::$type($messages);
    }

    if (isset($messages[Config::current_page()])) {
      $messages[Config::current_page()] = [];
      unset($messages[Config::current_page()]);
    }

    Session::set('request/messages', $messages);
  }
}
