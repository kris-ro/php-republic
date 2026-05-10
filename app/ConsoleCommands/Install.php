<?php

namespace App\ConsoleCommands;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;

class Install {

  use ConsoleIO;

  private $databaseName;
  private $databaseUsername;
  private $databasePassword;

  public function __construct() {
    $this->configDatabase();
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

    $this->validateConection();
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
    
  }
}