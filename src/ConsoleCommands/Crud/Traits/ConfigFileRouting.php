<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait ConfigFileRouting {

  private $adminRouting;

  public function updateRouting() {
    $lowerCaseControllerName = strtolower($this->controllerName);

    $this->loadConfig()->addAdminPaths($lowerCaseControllerName)->saveRouting();
  }

  private function loadConfig() {
    $jsonContent = file_get_contents($this->jsonRoutingPath);
    if (!($config = json_decode($jsonContent, true)) || json_last_error() != JSON_ERROR_NONE) {
      throw new \Exception(json_last_error_msg(), 1);
      return $this;
    }

    $this->adminRouting = $config;
    return $this;
  }

  private function addAdminPaths(string $lowerCaseControllerName) {
    if (!$this->adminRouting) {
      return $this;
    }

    $this->adminRouting['routes']['admin'][$lowerCaseControllerName] = "App\\Actions\\{$this->controllerName}\\Index";
    $this->adminRouting['routes']['admin'][$lowerCaseControllerName . '/' . 'add'] = "App\\Actions\\{$this->controllerName}\\Add";
    $this->adminRouting['routes']['admin'][$lowerCaseControllerName . '/' . 'update'] = "App\\Actions\\{$this->controllerName}\\Update";
    $this->adminRouting['routes']['admin'][$lowerCaseControllerName . '/' . 'delete'] = "App\\Actions\\{$this->controllerName}\\Delete";

    return $this;
  }

  private function saveRouting() {
    if (!$this->adminRouting) {
      return $this;
    }

    $jsonContent = json_encode($this->adminRouting, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($this->jsonRoutingPath, $jsonContent);

    return $this;
  }
}