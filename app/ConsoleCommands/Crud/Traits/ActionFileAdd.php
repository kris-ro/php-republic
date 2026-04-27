<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

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

    if (!file_exists(dirname($adminViewPath))) {
      mkdir(dirname($adminViewPath));
      chmod(dirname($adminViewPath), 0755);
    }

    file_put_contents($adminViewPath, $this->createAddActionTemplateFileContent($lowerCaseControllerName) . PHP_EOL);
  }

  private function createAddActionFileContent(string $lowerCaseControllerName): string {
    return '<?php'
                     . PHP_EOL . PHP_EOL
                     . "namespace App\Actions\\{$this->controllerName};" . PHP_EOL . PHP_EOL
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
    return '<?php'
                     . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Request;' . PHP_EOL
                     . '?>' . PHP_EOL
                     . '<!--begin::App Content Header-->' . PHP_EOL
                     . '<div class="app-content-header">' . PHP_EOL
                     . '  <!--begin::Container-->' . PHP_EOL
                     . '  <div class="container-fluid">' . PHP_EOL
                     . '    <!--begin::Row-->' . PHP_EOL
                     . '    <div class="row">' . PHP_EOL
                     . '      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; ' . Strings::prettify($this->modelName) . ' &raquo; Add</h3></div>' . PHP_EOL
                     . '    </div>' . PHP_EOL
                     . '    <!--end::Row-->' . PHP_EOL
                     . '  </div>' . PHP_EOL
                     . '  <!--end::Container-->' . PHP_EOL
                     . '</div>' . PHP_EOL
                     . '<!--end::App Content Header-->' . PHP_EOL . PHP_EOL
                     . '<!--begin::App Content-->' . PHP_EOL
                     . '<div class="app-content">' . PHP_EOL
                     . '  <!--begin::Container-->' . PHP_EOL
                     . '  <div class="container-fluid">' . PHP_EOL
                     . '    <!--begin::Row-->' . PHP_EOL
                     . '    <div class="row">' . PHP_EOL
                     . '      <!--begin::Col-->' . PHP_EOL
                     . '      <div class="col-sm-12">' . PHP_EOL
                     . '        <div class="card card-primary card-outline mb-4">' . PHP_EOL
                     . '          <!--begin::Header-->' . PHP_EOL
                     . '          <div class="card-header"><div class="card-title">Add ' . Strings::prettify($this->modelName) . '</div></div>' . PHP_EOL
                     . '          <!--end::Header-->' . PHP_EOL
                     . '          <!--begin::Form-->' . PHP_EOL
                     . '          <form action="/admin/' . $lowerCaseControllerName . '/add" method="POST">' . PHP_EOL
                     . '            <?php echo self::getFormToken(\'add' . strtolower($this->modelName) . '\') // self is instance of KrisRo\PhpRepublic\Template ?>' . PHP_EOL
                     . '            <!--begin::Body-->' . PHP_EOL
                     . '            <div class="card-body">' . PHP_EOL
                     .                $this->formElements(14, $this->autoIncrement) . PHP_EOL
                     . '            </div>' . PHP_EOL
                     . '            <!--end::Body-->' . PHP_EOL
                     . '            <!--begin::Footer-->' . PHP_EOL
                     . '            <div class="card-footer">' . PHP_EOL
                     . '              <button type="submit" class="btn btn-primary">Submit</button>' . PHP_EOL
                     . '            </div>' . PHP_EOL
                     . '            <!--end::Footer-->' . PHP_EOL
                     . '          </form>' . PHP_EOL
                     . '          <!--end::Form-->' . PHP_EOL
                     . '        </div>' . PHP_EOL
                     . '        <!--end::Card-->' . PHP_EOL
                     . '      </div>' . PHP_EOL
                     . '      <!--end::Col-->' . PHP_EOL
                     . '    </div>' . PHP_EOL
                     . '    <!--end::Row-->' . PHP_EOL
                     . '  </div>' . PHP_EOL
                     . '  <!--end::Container-->' . PHP_EOL
                     . '</div>' . PHP_EOL
                     . '<!--end::App Content-->' . PHP_EOL
    ;
  }
}