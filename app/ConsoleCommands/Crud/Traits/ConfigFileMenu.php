<?php

namespace App\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;
use KrisRo\PhpRepublic\Template;

trait ConfigFileMenu {

  private $htmlMenu;

  public function updateMenu() {
    $lowerCaseControllerName = strtolower($this->controllerName);

    $this->loadMenu()->addMenuItem($lowerCaseControllerName)->saveMenu();
  }

  private function loadMenu() {
    $this->htmlMenu = file($this->htmlMenuPath);

    return $this;
  }

  private function addMenuItem($lowerCaseControllerName) {
    $ulIndex = $this->getUlEnd();
    
    $newMenu = array_slice($this->htmlMenu, 0, $ulIndex);

    $this->htmlMenu = array_merge($newMenu, $this->newItems($lowerCaseControllerName), array_slice($this->htmlMenu, $ulIndex));

    return $this;
  }

  private function saveMenu() {
    file_put_contents($this->htmlMenuPath, implode('', $this->htmlMenu));
  }

  private function newItems($lowerCaseControllerName) {
    return [Template::load($this->htmlPath . 'side_menu_item.php', [
      'menu_item_name' => Strings::prettify($this->modelName),
      'menu_item_path' => $lowerCaseControllerName,
    ])];
  }

  private function getUlEnd() {
    $endIndex = 0;
    foreach ($this->htmlMenu as $index => $value) {
      if (strpos(trim($value), '</ul>') !== false) {
        $endIndex = $index;
      }
    }

    if (!$endIndex) {
      throw new \Exception('Can not find side menu end');
      return;
    }

    return $endIndex;
  }
}