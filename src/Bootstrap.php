<?php

namespace KrisRo\PhpRepublic;

use \KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Authenticate;

class Bootstrap implements \KrisRo\PhpRepublic\Interfaces\Bootstrap {

  use \KrisRo\PhpRepublic\Traits\Logger;

  public $config;
  private $DS;

  /**
   * Bootstrapping application's core
   * Session start
   * Setting up exception treatment/logging
   * Loading application config
   */
  public function __construct() {
    $this->DS = DS;

    session_start();

    Exceptions::install();

    $this->config = new Config();

    $this->config
      // app configuration
      ->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}app.json")
      // this is the place to override data with local specific values
      ->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}local.json");

    $validatorClass = Config::get('app/validator');
    Config::set('validator', new $validatorClass());
  }

  /**
   * Bootstrapping web specific instructions
   * Setting POST processing
   * Setting logger
   * Setting database connection
   * Setting user environment, rights and authentication
   * Setting language and translations
   *
   * @return void
   */
  public function Web(): void {
    $this->config
      // post form validators
      ->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}post.json");

    Config::set('logger', self::getLogger());

    $this->setupDatabaseConnection();

    Authenticate::createSessionFromApiToken();

    $this->setLanguage();
    
    // route maping to controller/action
    // if no ACL specified in config files use default method
    if (!Config::get('app/acl') || !method_exists(Config::get('app/acl/0'), Config::get('app/acl/1'))) {
      $this->setACL();

    } else {
      $aclClass = Config::get('app/acl/0');
      $aclMethod = Config::get('app/acl/1');

      // if specified method failes, fallback to default method
      if (!(new $aclClass())->$aclMethod()) {
        $this->setACL();
      }
    }

    $this->config->loadConfigFile(APP_ROOT . $this->DS . Config::get('app/paths/translations') . $this->DS . (Session::language() ?: Config::get('app/default_language')) . '.json');

    Config::set('request_time', (new \DateTime())->setTimezone(new \DateTimeZone(Config::get('app/datetime/time_zone'))));
  }

  /**
   * Bootstrapping cron specific instructions
   * Setting logger
   * Setting database connection
   *
   * @return void
   */
  public function Console(): void {
    $this->config
      // cron scripts
      ->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}console.json");

    Config::set('logger', self::getLogger());

    $this->setupDatabaseConnection();
  }

  /**
   * Shooting down app
   *
   * @return void
   */
  public function shutDown(): void {

  }

  public function AcceptedContentType() {
    $acceptedContentType = Config::get('app/api_formats');

    if (in_array(strtolower(Request::header('Content-Type')), $acceptedContentType)) {
      Config::set('app/requestFormat', strtolower(Request::header('Content-Type')));
      return true;
    }

    $httpAcceptHeader = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');

    if ($httpAcceptHeader == '*/*') {
      return true;
    }

    if (in_array($httpAcceptHeader, $acceptedContentType)) {
      Config::set('app/requestFormat', $httpAcceptHeader);
      return true;
    }

    http_response_code(400);
    return false;
  }

  /**
   * Database connection setup and store in Config
   *
   * @return void
   */
  private function setupDatabaseConnection(): void {
    $databaseConfig = Config::get('app/database');
    if (!($databaseConfig['creator'] ?? null)) {
      return;
    }

    $dbModel = new $databaseConfig['creator']($databaseConfig);
    if (!($dbModel instanceof $databaseConfig['creator'])) {
      return;
    }

    Config::set('dbModel', $dbModel);
  }

  /**
   * Detect user's preferred language
   * and set the template folder accordingly
   *
   * @return void
   */
  private static function setLanguage(): void {
    if (in_array($requestedLanguage = Request::get('lang'), Config::get('app/languages'))) {
      Session::set('language', $requestedLanguage);

    } elseif (!Session::language() && in_array($browserLanguage = substr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''), 0, 2), Config::get('app/languages'))) {
      Session::set('language', $browserLanguage);

    } elseif(!Session::language()) {
      Session::set('language', Config::get('app/default_language'));
    }

    if (in_array(SECTION, Config::get('app/json_sections'))) {
      Config::set('app/paths/language_templates', Config::get('app/paths/templates') . '/' . SECTION . '/' . Config::get('app/api_folder_language'));
    } else {
      Config::set('app/paths/language_templates', Config::get('app/paths/templates') . '/' . SECTION . '/' . Session::language());
    }
  }

  /**
   * Set access rights based on the JSON config files
   *
   * @return void
   */
  private function setACL(): void {
    // 'guest', 'customer', 'company', 'app'
    switch (Session::get('user/role')) {
      case 'app':
        $this->config->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}routes{$this->DS}app{$this->DS}authenticated.json");
        break;

      case 'company':
        $this->config->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}routes{$this->DS}admin{$this->DS}authenticated.json");
        break;

      case 'customer':
        $this->config->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}routes{$this->DS}front{$this->DS}authenticated.json");
        break;
    }

    $this->config->loadConfigFile(APP_ROOT . "{$this->DS}app{$this->DS}Config{$this->DS}routes{$this->DS}front{$this->DS}guest.json");
  }
}
