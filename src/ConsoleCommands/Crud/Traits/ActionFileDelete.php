<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait ActionFileDelete {

  public function buildDelete() {
    $lowerCaseControllerName = strtolower($this->controllerName);
    $itemName = lcfirst(Strings::toCamelCase($this->modelName));

    $adminViewPath = $this->adminViewPath . DS . 'delete.php';

    file_put_contents($adminViewPath, $this->createDeleteActionTemplateFileContent($lowerCaseControllerName) . PHP_EOL);

    file_put_contents($this->actionPath . DS . 'Delete.php', $this->createDeleteActionFileContent($lowerCaseControllerName, $itemName) . PHP_EOL);

    // file_put_contents(APP_ROOT . DS . 'public_html' . DS . 'admin' . DS . 'css' . DS . $lowerCaseControllerName . '_delete.css', '');
  }

  private function createDeleteActionFileContent(string $lowerCaseControllerName, string $itemName): string {
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
                     . "class Delete extends {$this->controllerName}Controller {" . PHP_EOL . PHP_EOL
                     . '  public function run(): string {' . PHP_EOL
                     . '    $' . $itemName . 'Id = Request::nth(4);' . PHP_EOL
                     . '    if (!(Config::validator() ?? (new Validator()))->positiveInteger($' . $itemName . 'Id) || !($' . $itemName . ' = (new ' . Strings::toCamelCase($this->modelName) . '())->get' . Strings::toCamelCase($this->modelName) . 'By' . ucfirst(Strings::toCamelCase($this->primaryKey)) . '($' . $itemName . 'Id))) {' . PHP_EOL
                     . '      Messages::send_popup(\'Invalid ' . Strings::prettify($this->primaryKey) . ' ID\');' . PHP_EOL
                     . '      Request::redirect(\'/admin/' . $lowerCaseControllerName . '\');' . PHP_EOL
                     . '    }' . PHP_EOL . PHP_EOL
                     . '    /**' . PHP_EOL
                     . "     * Uncomment the lines bellow if you need CCS and JS. " . PHP_EOL
                     . "     * This is mapped to public_html/admin/css/{$lowerCaseControllerName}_delete.css" . PHP_EOL
                     . "     * and public_html/admin/js/{$lowerCaseControllerName}_delete.js" . PHP_EOL . PHP_EOL
                     . "     * Config::set('css/{$lowerCaseControllerName}/delete', '{$lowerCaseControllerName}_delete.css');" . PHP_EOL
                    . "      * Config::set('js/{$lowerCaseControllerName}/delete', '{$lowerCaseControllerName}_delete.js');" . PHP_EOL
                     . '     */' . PHP_EOL . PHP_EOL
                     .      $this->binary2hex(4, $itemName) . PHP_EOL
                     . "    return Template::renderView('/admin/' . Session::language() . '/{$lowerCaseControllerName}/delete.php', [" . PHP_EOL
                     . '      \'item\' => $' . $itemName . ',' . PHP_EOL
                     . '      \'errors\' => Messages::delete' . strtolower($this->modelName) . 'form_error(),' . PHP_EOL
                     . '    ]);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL
                     . '}' . PHP_EOL . PHP_EOL
    ;
  }

  private function createDeleteActionTemplateFileContent(string $lowerCaseControllerName): string {
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
                     . '      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; ' . Strings::prettify($this->modelName) . ' &raquo; Delete</h3></div>' . PHP_EOL
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
                     . '          <div class="card-header"><div class="card-title">Delete ' . Strings::prettify($this->modelName) . '</div></div>' . PHP_EOL
                     . '          <!--end::Header-->' . PHP_EOL
                     . '          <div class="alert alert-warning m-3" role="alert">' . PHP_EOL
                     . '            <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>' . PHP_EOL
                     . '            You\'re deleting ' . Strings::prettify($this->modelName) . ' item. You won\'t be able to undo this.' . PHP_EOL
                     . '          </div>' . PHP_EOL
                     . '          <!--begin::Form-->' . PHP_EOL
                     . '          <form action="/admin/' . $lowerCaseControllerName . '/delete/<?php echo self::view(\'item/' . $this->primaryKey . '\') ?>" method="POST">' . PHP_EOL
                     . '            <?php echo self::getFormToken(\'delete' . strtolower($this->modelName) . '\') // self is instance of KrisRo\PhpRepublic\Template ?>' . PHP_EOL
                     . '            <!--begin::Body-->' . PHP_EOL
                     . '            <div class="card-body">' . PHP_EOL
                     . '              <input type="hidden" name="' . $this->primaryKey . '" id="' . $this->primaryKey . '-id" value="<?php echo self::view(\'item/' . $this->primaryKey . '\') ?>">' . PHP_EOL
                     .                $this->detailItems(14) . PHP_EOL
                     . '            </div>' . PHP_EOL
                     . '            <!--end::Body-->' . PHP_EOL
                     . '            <!--begin::Footer-->' . PHP_EOL
                     . '            <div class="card-footer">' . PHP_EOL
                     . '              <button type="submit" class="btn btn-danger">Delete</button>' . PHP_EOL
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