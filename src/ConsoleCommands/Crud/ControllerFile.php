<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud;

use KrisRo\PhpRepublic\Strings;

class ControllerFile {

  private $controllerName;
  private $controllerPath;

  public function __construct(\KrisRo\PhpRepublic\ConsoleCommands\Crud $crud) {
    $this->controllerName = $crud->controllerName;
    $this->controllerPath = $crud->controllerPath;
  }

  public function buildController() {
    $fileContent = '<?php'
                     . PHP_EOL . PHP_EOL
                     . 'namespace App\Controllers;' . PHP_EOL . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Controller;' . PHP_EOL . PHP_EOL
                     . 'class ' . Strings::toCamelCase($this->controllerName) . ' extends Controller {}' . PHP_EOL;


    file_put_contents($this->controllerPath, $fileContent . PHP_EOL);

    return $this->controllerName;
  }
}