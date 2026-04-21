<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait ActionFileList {

  public function buildList() {
    $lowerCaseControllerName = strtolower($this->controllerName);

    if (!file_exists(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName)) {
      mkdir(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName);
      chmod(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName, 0755);
    }

    file_put_contents(APP_ROOT . DS . 'app' . DS . 'Actions' . DS . $this->controllerName . DS . 'Index.php', $this->createListActionFileContent($lowerCaseControllerName) . PHP_EOL);

    file_put_contents(APP_ROOT . DS . 'public_html' . DS . 'admin' . DS . 'css' . DS . $lowerCaseControllerName . '_index.css', '');

    $adminViewPath = APP_ROOT
                    . DS . 'app'
                    . DS . 'views'
                    . DS . 'admin'
                    . DS . 'en' // always 'en'
                    . DS . $lowerCaseControllerName
                    . DS . 'index.php';

    if (!file_exists(dirname($adminViewPath))) {
      mkdir(dirname($adminViewPath));
      chmod(dirname($adminViewPath), 0755);
    }

    file_put_contents($adminViewPath, $this->createListActionTemplateFileContent($lowerCaseControllerName) . PHP_EOL);
  }

  private function createListActionFileContent(string $lowerCaseControllerName): string {
    $table = strtolower($this->modelName);
    return '<?php'
                     . PHP_EOL . PHP_EOL
                     . "namespace App\Post\\{$this->controllerName};" . PHP_EOL . PHP_EOL
                     . "use App\Controllers\\{$this->controllerName} as {$this->controllerName}Controller;" . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Template;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Session;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Messages;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Listing;' . PHP_EOL
                     . 'use KrisRo\PhpRepublic\Dates;' . PHP_EOL
                     . 'use KrisRo\PhpConfig\Config;' . PHP_EOL . PHP_EOL
                     . "class Index extends {$this->controllerName}Controller {" . PHP_EOL . PHP_EOL
                     .    $this->listFilters(2) . PHP_EOL
                     . '  public function run(): string {' . PHP_EOL
                     . '    /**' . PHP_EOL
                     . "     * This is mapped to public_html/admin/css/{$lowerCaseControllerName}_index.css" . PHP_EOL
                     . '     */' . PHP_EOL
                     . "    Config::set('css/{$lowerCaseControllerName}_index', '{$lowerCaseControllerName}_index.css');" . PHP_EOL . PHP_EOL
                     . '    $list = new Listing([' . PHP_EOL
                     . '      \'order-by\' => \'id\',' . PHP_EOL
                     . '      \'size\' => \'10\',' . PHP_EOL
                     . '      \'sort\' => \'asc\',' . PHP_EOL
                     . '    ], $this->filters);' . PHP_EOL . PHP_EOL
                     . '    $list->select([\'`' . $table . '`.*\'])' . PHP_EOL
                     . '         ->from(\'' . $table . '\')' . PHP_EOL
                     .           $this->listingObjectFilters(9) . PHP_EOL
                     . '    $listData = $list->getData();' . PHP_EOL . PHP_EOL
                     . '    $items = [];' . PHP_EOL
                     . '    foreach ($listData[\'data\'] as $item) {' . PHP_EOL
                     . '      $items[$item[\'' . $this->primaryKey . '\']] = [' . PHP_EOL
                     .          $this->listingObjectDataPrep(8) . PHP_EOL
                     . '      ] + $item;' . PHP_EOL
                     . '    }' . PHP_EOL . PHP_EOL
                     . "    return Template::renderView('/admin/' . Session::language() . '/{$lowerCaseControllerName}/index.php', [" . PHP_EOL
                     . '      \'items\' => $items,' . PHP_EOL
                     . '    ]);' . PHP_EOL
                     . '  }' . PHP_EOL . PHP_EOL
                     . '}' . PHP_EOL . PHP_EOL
    ;
  }

  private function createListActionTemplateFileContent(string $lowerCaseControllerName) {
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
                     . '      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; ' . Strings::prettify($this->modelName) . '</h3></div>' . PHP_EOL
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
                     . '      <div class="col-sm-12 list-container" id="user-' . $lowerCaseControllerName . '-list-container" data-list-address="<?php echo self::get(\'list_address\') ?>">' . PHP_EOL
                     . '        <div class="card mb-4 list-content" id="user-' . $lowerCaseControllerName . '-list-content">' . PHP_EOL
                     . '          <div class="card-body">' . PHP_EOL
                     . '            <table id="user-' . $lowerCaseControllerName . '" class="table table-bordered table-striped table-hover dataTable dtr-inline" aria-describedby="' . $lowerCaseControllerName . '_info">' . PHP_EOL
                     . '              <thead>' . PHP_EOL
                     . '                <tr>' . PHP_EOL
                     .                    $this->listTableHeader() . PHP_EOL
                     . '                </tr>' . PHP_EOL
      ;
  }
}