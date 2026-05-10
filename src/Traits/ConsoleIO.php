<?php

namespace KrisRo\PhpRepublic\Traits;

use KrisRo\PhpRepublic\Resources;
use KrisRo\PhpConfig\Config;
use KrisRo\PhpDependencyInjection\Container;

trait ConsoleIO {

  protected $args = null;
  protected $action = null;

  /**
   * Read and validate requested action name
   */
  protected function getAction() {
    $this->args = array_slice(Resources::globals('argv'), 1);
    $this->action = array_shift($this->args);

    if (!Config::get("console/{$this->action}")) {
      Config::logger()->error('Invalid cron/console operation');
      exit;
    }

    if (!class_exists(Config::get("console/{$this->action}/class") ?? '')) {
      Config::logger()->error('Invalid cron/console config, missing class or method');
      exit;
    }

    $this->buildContainerConfig();
  }

  /**
   * Execute requested action
   *
   * @return void
   */
  public function run(): void {
    try {
      Container::service('_console_' . $this->action);
    } catch (\Exception $e) {
      self::echoError($e->getMessage());
    }
  }

  /**
   * Build the config array required
   * for KrisRo\PhpDependencyInjection\Container
   */
  private function buildContainerConfig() {
    $actionConfig = Config::get("console/{$this->action}");

    $container = [
      'class' => $actionConfig['class'],
    ];

    unset($actionConfig['class']);

    if (empty($actionConfig)) {
      return;
    }

    foreach ($actionConfig as $method => $params) {
      $container[$method] = $this->getInputParams($params);
    }

    Config::set('services/_console_' . $this->action, $container);
  }

  /**
   * Read and validate input arguments for the requested action
   */
  protected function getInputParams(array $params) {
    if (empty($params)) {
      return [];
    }

    $inputParams = [];
    foreach ($this->args as $arg) {
      if (!str_starts_with($arg, '--')) {
        continue;
      }

      list($key, $value) = explode('=', ltrim($arg, '-'), 2);
      if (in_array(substr($params[$key] ?? '', 0, 1), ['@', '#'])) { // get arguments from config
        $inputParams[$key] = $params[$key];
        unset($params[$key]);
        continue;
      }

      if (!array_key_exists($key, $params)) { // ignore unknown arguments
        continue;
      }

      $inputParams[$key] = $value;
      unset($params[$key]);
    }

    if (empty($params)) {
      return $inputParams;
    }

    foreach ($params as $key => $value) {
      if (!$value) { // treat as optional
        continue;
      }

      $inputParams[$key] = $value; // treat as default value
    }

    return $inputParams;
  }

  public static function consoleFormat(array $format, string $text) {
    $codes = [
      'bold' => 1, 'underline' => 4,
      'black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 93,
      'blue' => 94, 'magenta' => 35, 'cyan' => 36, 'white' => 37,
      'blackbg' => 40, 'redbg' => 41, 'greenbg' => 42, 'yellowbg' => 103,
      'bluebg' => 44, 'magentabg' => 45, 'cyanbg' => 46, 'lightgreybg' => 47
    ];

    $formatMap = array_map(fn($v) => $codes[$v], $format);

    return "\e[" . implode(';', $formatMap) . "m" . $text . "\e[0m";
  }

  public static function echoError(string $text) {
    echo self::consoleFormat(['bold', 'redbg'], ' ERROR: ') . ' '
       . self::consoleFormat(['red'], $text)
       . PHP_EOL;
  }

  public static function echoWarning(string $text) {
    echo self::consoleFormat(['bold', 'yellowbg'], ' WARNING: ') . ' '
       . self::consoleFormat(['yellow'], $text)
       . PHP_EOL;
  }

  public static function echoInfo(string $text) {
    echo self::consoleFormat(['bold', 'bluebg'], ' INFO: ') . ' '
       . self::consoleFormat(['white'], $text)
       . PHP_EOL;
  }
}