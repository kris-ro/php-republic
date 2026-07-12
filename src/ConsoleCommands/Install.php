<?php

namespace KrisRo\PhpRepublic\ConsoleCommands;

use KrisRo\PhpConfig\Config;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpRepublic\Exceptions;
use KrisRo\PhpRepublic\Crypto;

class Install {

  use ConsoleIO;

  private $databaseName;
  private $databaseUsername;
  private $databasePassword;
  private $databaseConfig;

  private $jsonLocalConfigPath;
  private $localConfig;

  private $dbModel;
  private $sqlPath;

  public function __construct() {
    Exceptions::$consolePrintOnly = true;

    $this->jsonLocalConfigPath = APP_ROOT . DS . 'app' . DS . 'Config' . DS . 'local.json';

    $this->sqlPath = APP_ROOT . DS . 'sql';

    if (!$this->loadConfig()) {
      self::echoErro("Can not read config data at {$this->jsonLocalConfigPath}.");
    }

    if (!$this->configDatabase()) {
      return;
    }

    if (!$this->importSql()) {
      return;
    }

    if (!$this->setEncryptionKeys()) {
      return;
    }

    if (!$this->setEmailAddress()) {
      return;
    }

    if (!$this->setDebug()) {
      return;
    }

    if (!$this->saveConfig()) {
      self::echoError("Can not save config data at {$this->jsonLocalConfigPath}.");
    }

    $this->printInstructions();
  }

  private function configDatabase() {
    while (!$this->databaseName) {
      $this->readDatabaseName();
    }

    while (!$this->databaseUsername) {
      $this->readDatabaseUsername();
    }

    while (!$this->databasePassword) {
      $this->readDatabasePassword();
    }

    return $this->validateConection();
  }

  private function readDatabaseName() {
    self::echoDefault('Enter database name: ');
    $this->databaseName = trim(fgets(STDIN));
  }

  private function readDatabaseUsername() {
    self::echoDefault('Enter database username: ');
    $this->databaseUsername = trim(fgets(STDIN));
  }

  private function readDatabasePassword() {
    shell_exec('stty -echo');
    self::echoDefault('Enter database password: ');
    $this->databasePassword = trim(fgets(STDIN));
    shell_exec('stty echo');
    self::echoDefault(PHP_EOL);
  }

  private function validateConection() {
    $this->databaseConfig = Config::get('app/database');
    if (!($this->databaseConfig['creator'] ?? null)) {
      $this->databaseConfig = [
        'creator' => '\\KrisRo\\PhpDatabaseModel\\Model',
        'host' => 'localhost',
      ];
    }

    $this->databaseConfig['database'] = $this->databaseName;
    $this->databaseConfig['username'] = $this->databaseUsername;
    $this->databaseConfig['password'] = $this->databasePassword;

    try {
      $this->dbModel = new $this->databaseConfig['creator']($this->databaseConfig);

      if (!($this->dbModel instanceof $this->databaseConfig['creator'])) {
        self::echoError("Can not connect to database {$this->databaseName}.");
        return false;
      }

      $this->localConfig['app']['database'] = $this->databaseConfig;

      return true;

    } catch (\PDOException $e) {
      self::echoError('Database connection failed: ' . $e->getMessage());
      return false;

    } catch (\Exception $e) {
      self::echoError('Database connection failed: ' . $e->getMessage());
      return false;
    }

    return false;
  }

  private function importSql() {
    try {
      foreach (scandir($this->sqlPath) as $file) {
        if (strpos($file, '.sql') === false) {
          continue;
        }

        if (!($sqlQuery = file_get_contents($this->sqlPath . DS . $file))) {
          self::echoError('Failed to read sql query at: ' . $this->sqlPath . DS . $file);
          return false;
        }

        self::echoDefault('Runing SQL query from: ' . $this->sqlPath . DS . $file . ' ... ');
        $this->dbModel->query($sqlQuery)->execute([])->rowCount();
        self::echoDefault('Done' . PHP_EOL);
      }

    } catch (\PDOException $e) {
      self::echoError('Failed to execute sql query: ' . $e->getMessage());
      return false;

    } catch (\Exception $e) {
      self::echoError('Failed to execute sql query: ' . $e->getMessage());
      return false;
    }

    return true;
  }

  private function setEmailAddress() {
    self::echoDefault('Enter site email address: ');
    $siteEmail = trim(fgets(STDIN));

    if (!$siteEmail || !(new Validator())->email($siteEmail)) {
      self::echoWarning('Invalid email. You need to set the email section manually in ' . $this->jsonLocalConfigPath);
      return true;
    }

    $this->localConfig['mail'] = [
      'test' => $siteEmail,
      'support' => $siteEmail,
      'system' => $siteEmail,
      'contact' => $siteEmail,
      'signature' => 'The team',
    ];

    return true;
  }

  private function setEncryptionKeys() {
    $iv = Crypto::generateOpenSslIv();
    $key = Crypto::generateEncryptionKey();

    if (!$iv || !$key) {
      self::echoError('Failed to create encryption keys. Make sure the openssl extension is isntalled.');
      return false;
    }

    $this->localConfig['crypto'] = [
      'cipher' => Config::get('install/cipher'),
      'key' => $key,
      'iv' => $iv,
    ];

    return true;
  }

  private function setDebug() {
    self::echoDefault('Enable debug (yes/no) ? ');
    self::echoWarning('Do not enable debug if you are on production environment (enter "no" to the console or just press Enter) !');

    if (trim(fgets(STDIN)) == 'yes') {
      $this->localConfig['_debug'] = true;
    } else {
      $this->localConfig['_debug'] = false;
    }

    return true;
  }

  private function printInstructions() {
    self::echoInfo('Settings were saved. You can edit these settings in ' . $this->jsonLocalConfigPath);
  }

  private function loadConfig() {
    $jsonContent = file_get_contents($this->jsonLocalConfigPath);
    if (!($config = json_decode($jsonContent, true)) || json_last_error() != JSON_ERROR_NONE) {
      self::echoError(json_last_error_msg());
      return false;
    }

    $this->localConfig = $config;

    return true;
  }

  private function saveConfig() {
    if (!$this->localConfig) {
      return false;
    }

    $jsonContent = json_encode($this->localConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    return file_put_contents($this->jsonLocalConfigPath, $jsonContent);
  }
}