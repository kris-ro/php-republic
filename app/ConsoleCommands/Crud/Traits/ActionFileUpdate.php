<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait ActionFileUpdate {

  public function buildUpdate() {
    $lowerCaseControllerName = strtolower($this->controllerName);
    $itemName = lcfirst(Strings::toCamelCase($this->modelName));

    if (!file_exists(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName)) {
      mkdir(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName);
      chmod(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName, 0755);
    }

    $adminViewPath = APP_ROOT
                    . DS . 'app'
                    . DS . 'views'
                    . DS . 'admin'
                    . DS . 'en' // always 'en'
                    . DS . $lowerCaseControllerName
                    . DS . 'update.php';

    if (!file_exists(dirname($adminViewPath))) {
      mkdir(dirname($adminViewPath));
      chmod(dirname($adminViewPath), 0755);
    }

    file_put_contents($adminViewPath, $this->createUpdateActionTemplateFileContent($lowerCaseControllerName) . PHP_EOL);

    file_put_contents(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName . DS . 'Update.php', $this->createUpdateActionFileContent($lowerCaseControllerName, $itemName) . PHP_EOL);

    file_put_contents(APP_ROOT . DS . 'public_html' . DS . 'admin' . DS . 'css' . DS . $lowerCaseControllerName . '_update.css', '');
  }

  private function createUpdateActionFileContent(string $lowerCaseControllerName, string $itemName): string {
    return '<?php'
                     . PHP_EOL . PHP_EOL
                     . "namespace App\Actions\\{$this->controllerName};" . PHP_EOL . PHP_EOL
                     . "use App\Controllers\\{$this->controllerName} as {$this->controllerName}Controller;" . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Template;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Session;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Messages;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Request;' . PHP_EOL
                     . 'use KrisRo\Validator\Validator;' . PHP_EOL
                     . 'use App\Models\\' . Strings::toCamelCase($this->modelName) . ';' . PHP_EOL
                     . 'use KrisRo\PhpConfig\Config;' . PHP_EOL . PHP_EOL
                     . "class Update extends {$this->controllerName}Controller {" . PHP_EOL . PHP_EOL
                     . '  public function run(): string {' . PHP_EOL
                     . '    $' . $itemName . 'Id = Request::nth(4);' . PHP_EOL
                     . '    if (!(Config::validator() ?? (new Validator()))->positiveInteger($' . $itemName . 'Id) || !($' . $itemName . ' = (new ' . Strings::toCamelCase($this->modelName) . '())->get' . Strings::toCamelCase($this->modelName) . 'By' . ucfirst(Strings::toCamelCase($this->primaryKey)) . '($' . $itemName . 'Id))) {' . PHP_EOL
                     . '      Messages::send_popup(\'Invalid ' . Strings::prettify($this->primaryKey) . ' ID\');' . PHP_EOL
                     . '      Request::redirect(\'/admin/' . $lowerCaseControllerName . '\');' . PHP_EOL
                     . '    }' . PHP_EOL . PHP_EOL
                     . '    /**' . PHP_EOL
                     . "     * This is mapped to public_html/admin/css/{$lowerCaseControllerName}_update.css" . PHP_EOL
                     . '     */' . PHP_EOL
                     . "    Config::set('css/{$lowerCaseControllerName}_update', '{$lowerCaseControllerName}_update.css');" . PHP_EOL . PHP_EOL
                     .      $this->binary2hex(4, $itemName) . PHP_EOL
                     . "    return Template::renderView('/admin/' . Session::language() . '/{$lowerCaseControllerName}/update.php', [" . PHP_EOL
                     . '      \'item\' => $' . $itemName . ',' . PHP_EOL
                     . '      \'errors\' => Messages::update' . strtolower($this->modelName) . 'form_error(),' . PHP_EOL
                     . '    ]);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL
                     . '}' . PHP_EOL . PHP_EOL
    ;
  }

  private function createUpdateActionTemplateFileContent(string $lowerCaseControllerName): string {
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
                     . '      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; ' . Strings::prettify($this->modelName) . ' &raquo; Update</h3></div>' . PHP_EOL
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
                     . '          <div class="card-header"><div class="card-title">Update ' . Strings::prettify($this->modelName) . '</div></div>' . PHP_EOL
                     . '          <!--end::Header-->' . PHP_EOL
                     . '          <!--begin::Form-->' . PHP_EOL
                     . '          <form action="/admin/' . $lowerCaseControllerName . '/update/<?php echo self::view(\'item/' . $this->primaryKey . '\') ?>" method="POST" enctype="multipart/form-data">' . PHP_EOL
                     . '            <?php echo self::getFormToken(\'update' . strtolower($this->modelName) . '\') // self is instance of KrisRo\PhpRepublic\Template ?>' . PHP_EOL
                     . '            <!--begin::Body-->' . PHP_EOL
                     . '            <div class="card-body">' . PHP_EOL
                     . '              <input type="hidden" name="' . $this->primaryKey . '" id="' . $this->primaryKey . '-id" value="<?php echo self::view(\'item/' . $this->primaryKey . '\') ?>">' . PHP_EOL
                     .                $this->formElements(14, $this->autoIncrement + [999 => $this->primaryKey]) . PHP_EOL
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