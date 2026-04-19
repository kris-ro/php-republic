<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Session;

trait ActionFileAdd {

  public function buildAdd() {
    $lowerCaseControllerName = strtolower($this->controllerName);

    if (!file_exists(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName)) {
      mkdir(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName);
      chmod(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName, 0755);
    }

    file_put_contents(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName . DS . 'Add.php', $this->createAddActionFileContent($lowerCaseControllerName) . PHP_EOL);

    file_put_contents(APP_ROOT . DS . 'public_html' . DS . 'admin' . DS . 'css' . DS . $lowerCaseControllerName . '_add.css', '');

    $adminViewPath = APP_ROOT 
                    . DS . 'app' 
                    . DS . 'views' 
                    . DS . 'admin' 
                    . DS . 'en' // always 'en' 
                    . DS . $lowerCaseControllerName 
                    . DS . 'add.php';

    if (!file_exists($adminViewPath)) {
      mkdir(dirname($adminViewPath));
      chmod(dirname($adminViewPath), 0755);
    }

    file_put_contents($adminViewPath, $this->createAddActionTemplateFileContent($lowerCaseControllerName) . PHP_EOL);
  }

  private function createAddActionFileContent(string $lowerCaseControllerName): string {
    return '<?php'
                     . PHP_EOL . PHP_EOL
                     . "namespace App\Post\\{$this->controllerName};" . PHP_EOL . PHP_EOL
                     . "use App\Controllers\\{$this->controllerName} as {$this->controllerName}Controller;" . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Template;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Session;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Messages;' . PHP_EOL
                     . 'use KrisRo\PhpConfig\Config;' . PHP_EOL . PHP_EOL
                     . "class Add extends {$this->controllerName}Controller {" . PHP_EOL . PHP_EOL
                     . '  public function run(): string {' . PHP_EOL
                     . '    /**' . PHP_EOL
                     . "     * This is mapped to public_html/admin/css/{$lowerCaseControllerName}_add.css" . PHP_EOL
                     . '     */' . PHP_EOL
                     . "    Config::set('css/{$lowerCaseControllerName}_add', '{$lowerCaseControllerName}_add.css');" . PHP_EOL . PHP_EOL
                     . "    return Template::renderView('/admin/' . Session::language() . '/{$lowerCaseControllerName}/add.php', [" . PHP_EOL
                     . '      \'errors\' => Messages::add' . strtolower($this->modelName) . 'form_error(),' . PHP_EOL
                     . '    ]);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL
                     . '}' . PHP_EOL . PHP_EOL
    ;
  }

  private function createAddActionTemplateFileContent(string $lowerCaseControllerName): string {
    return 'test';
  }
}