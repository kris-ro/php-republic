<?php

namespace KrisRo\PhpRepublic\ConsoleCommands\Crud\Traits;

use KrisRo\PhpRepublic\Strings;

trait ConfigFilePost {

  private $postProcessors;

  public function updatePost() {
    $lowerCaseControllerName = strtolower($this->controllerName);

    $this->loadPost()->addPostProcessors($lowerCaseControllerName)->savePost();
  }

  private function loadPost() {
    $jsonContent = file_get_contents($this->jsonPostPath);
    if (!($config = json_decode($jsonContent, true)) || json_last_error() != JSON_ERROR_NONE) {
      throw new \Exception(json_last_error_msg(), 1);
      return $this;
    }

    $this->postProcessors = $config;
    return $this;
  }

  private function addPostProcessors(string $lowerCaseControllerName) {
    if (!$this->postProcessors) {
      return $this;
    }

    $this->postProcessors['post']['admin'][$lowerCaseControllerName . '/' . 'add'] = "App\\Post\\{$this->controllerName}\\Add";
    $this->postProcessors['post']['admin'][$lowerCaseControllerName . '/' . 'update'] = "App\\Post\\{$this->controllerName}\\Update";
    $this->postProcessors['post']['admin'][$lowerCaseControllerName . '/' . 'delete'] = "App\\Post\\{$this->controllerName}\\Delete";

    return $this;
  }

  private function savePost() {
    if (!$this->postProcessors) {
      return $this;
    }

    $jsonContent = json_encode($this->postProcessors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($this->jsonPostPath, $jsonContent);

    return $this;
  }
}