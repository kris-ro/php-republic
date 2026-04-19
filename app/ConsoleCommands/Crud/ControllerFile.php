<?php

namespace App\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;

class ControllerFile {

  private $controllerName;

  public function __construct(string $controllerName) {
    $this->controllerName = ucfirst(Strings::toCamelCase($controllerName)) . 's';
  }

  public function buildController() {
    $fileContent = '<?php'
                     . PHP_EOL . PHP_EOL
                     . 'namespace App\Controllers;' . PHP_EOL . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Controller;' . PHP_EOL . PHP_EOL
                     . 'class ' . Strings::toCamelCase($this->controllerName) . ' extends Controller {}' . PHP_EOL;


    file_put_contents(APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . Strings::toCamelCase($this->controllerName) . '.php', $fileContent . PHP_EOL);

    return $this->controllerName;
  }
}