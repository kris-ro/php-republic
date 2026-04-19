<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait PostFileAdd {

  public function buildAdd() {
    $this->validationMethods = [];

    $fileContent = '<?php'
                     . PHP_EOL . PHP_EOL
                     . "namespace App\Post\\{$this->controllerName};" . PHP_EOL . PHP_EOL
                     . 'use KrisRo\PhpConfig\Config;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Request;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Translate;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Messages;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Session;' . PHP_EOL
                     . 'use KrisRo\Validator\Validator;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Traits\CSRF;' . PHP_EOL . PHP_EOL
                     . 'class Add implements PostDataProcessor {' . PHP_EOL . PHP_EOL
                     . '  use CSRF;' . PHP_EOL . PHP_EOL
                     . '  static protected $formId = \'add' . strtolower($this->modelName) . '\';' . PHP_EOL . PHP_EOL
                     . '  public static function ValidatePostData(): void {' . PHP_EOL
                     . '    $result = Config::validator()' . PHP_EOL
                     . '              ->addPostValidationMessages([' . PHP_EOL
                     .                  $this->buildFieldsMessages(16, $this->autoIncrement) . PHP_EOL
                     . '              ])' . PHP_EOL
                     . '              ->addPostValidationRules([' . PHP_EOL
                     .                  $this->buildFieldsValidation(16, $this->autoIncrement) . PHP_EOL
                     . '              ])' . PHP_EOL
                     . '              ->processPost();' . PHP_EOL . PHP_EOL
                     . '    if (!$result) {' . PHP_EOL
                     . '      Messages::add' . strtolower($this->modelName) . 'form_error(Config::validator()->getPostValidationMessages());' . PHP_EOL
                     . '    }' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL
                     . '  public static function ProcessPostData(): void {' . PHP_EOL
                     . '    if (!empty(Config::validator()->getPostValidationMessages())) {' . PHP_EOL
                     . '      return;' . PHP_EOL
                     . '    }' . PHP_EOL . PHP_EOL
                     . '    (new \App\Models\\' . Strings::toCamelCase($this->modelName) . '())->set' . Strings::toCamelCase($this->modelName) . '(Config::validator()->getPost());' . PHP_EOL . PHP_EOL
                     . '    Session::set(\'request/messages/' . strtolower($this->controllerName) . '/popup_success\', Translate::' . strtolower($this->modelName) . '(\'' . ucfirst(strtolower(str_replace('_', ' ', $this->modelName))) . ' was saved\'));' . PHP_EOL . PHP_EOL
                     . '    Request::redirect(\'/' . strtolower($this->controllerName) . '\');' . PHP_EOL
                     . '  }' . PHP_EOL
                     .    implode(PHP_EOL, $this->validationMethods) . PHP_EOL
                     . '}' . PHP_EOL . PHP_EOL
      ;

    if (!file_exists(APP_ROOT . DS . 'app' . DS . 'Post' . DS . $this->controllerName)) {
      mkdir(APP_ROOT . DS . 'app' . DS . 'Post' . DS . $this->controllerName);
      chmod(APP_ROOT . DS . 'app' . DS . 'Post' . DS . $this->controllerName, 0755);
    }

    file_put_contents(APP_ROOT . DS . 'app' . DS . 'Post' . DS . $this->controllerName . DS . 'Add.php', $fileContent . PHP_EOL);
  }
}