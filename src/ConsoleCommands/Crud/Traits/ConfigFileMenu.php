<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;

trait ConfigFileMenu {

  private $htmlMenu;
  private $ulIndex;
  private $lowerCaseControllerName;

  public function updateMenu(): void {
    $this->lowerCaseControllerName = strtolower($this->controllerName);

    $this->loadMenu()->addMenuItem()->saveMenu();
  }

  private function loadMenu(): self {
    $this->htmlMenu = file($this->htmlMenuPath);

    $this->ulIndex = $this->getUlEnd();

    return $this;
  }

  private function addMenuItem(): self {
    if (!$this->ulIndex) {
      return $this;
    }

    $newMenu = array_slice($this->htmlMenu, 0, $this->ulIndex);

    $this->htmlMenu = array_merge($newMenu, $this->newItems(), array_slice($this->htmlMenu, $this->ulIndex));

    return $this;
  }

  private function saveMenu(): void {
    if (!$this->ulIndex) {
      return;
    }

    file_put_contents($this->htmlMenuPath, implode('', $this->htmlMenu));
  }

  private function newItems(): array {
    return [Template::load($this->htmlPath . 'side_menu_item.php', [
      'menu_item_name' => Strings::prettify($this->modelName),
      'menu_item_path' => $this->lowerCaseControllerName,
    ])];
  }

  private function getUlEnd(): bool|int {
    $endIndex = 0;

    foreach ($this->htmlMenu as $index => $value) {
      if (strpos($value, '/admin/' . $this->lowerCaseControllerName) !== false) {
        return false;
      }

      if (strpos(trim($value), '</ul>') !== false) {
        $endIndex = $index;
      }
    }

    if (!$endIndex) {
      throw new \Exception('Can not find side menu end');
    }

    return $endIndex;
  }
}