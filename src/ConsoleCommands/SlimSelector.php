<?php

namespace KrisRo\PhpRepublic\ConsoleCommands;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Traits\ConsoleIO;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpRepublic\Exceptions;
use KrisRo\PhpRepublic\Crypto;
use KrisRo\PhpRepublic\Arrays;

class SlimSelector {

  use ConsoleIO;

  private $templatePath;
  private $dom;
  private $elementName;
  private $inputNode;
  private $targetLink;
  private $adminRoutes;

  private $webroot;
  
  public function __construct() {
    Exceptions::$consolePrintOnly = true;

    if (!$this->getWebroot()) {
      return $this;
    }

    if (!$this->getTemplatePath()) {
      return $this;
    }

    if (!$this->getElementName()) {
      return $this;
    }

    if (!$this->getTargetLink()) {
      return $this;
    }

    $this->modifyNode();

    $this->saveHtml();
  }

  private function getWebroot() {
    self::echoDefault('Enter the site domain: ');
    if (!($this->webroot = trim(fgets(STDIN))) || !Config::validator()->website($this->webroot)) {
      self::echoError('Invalid webroot : ' . $this->webroot);
      return false;
    }

    return true;
  }

  private function getTemplatePath() {
    self::echoDefault('Enter template path starting with controller name e.g. "users/add.php": ');
    $this->templatePath = trim(fgets(STDIN));

    if (!file_exists(Config::get('app/paths/views') . '/admin/en/' . $this->templatePath)) {
      self::echoError('Invalid template path : ' . Config::get('app/paths/views') . '/admin/en/' . $this->templatePath);
      return false;
    }

    if (!($html = file_get_contents(Config::get('app/paths/views') . '/admin/en/' . $this->templatePath))) {
      self::echoError('Empty file at : ' . Config::get('app/paths/views') . '/admin/en/' . $this->templatePath);
      return false;
    }

    $this->dom = new \DOMDocument();

    // Deactivate errors to silence warnings about HTML5/Bootstrap structure
    libxml_use_internal_errors(true);

    $this->dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    libxml_clear_errors();

    return true;
  }

  private function getElementName() {
    self::echoDefault('Enter form element name e.g. "email_address": ');
    $this->elementName = trim(fgets(STDIN));

    if (!$this->elementName) {
      self::echoError('Invalid element name');
      return false;
    }

    // use xPath for searching the element
    $xpath = new \DOMXPath($this->dom);

    $this->inputNode = $xpath->query('//input[@name="' . $this->elementName . '"]')->item(0);

    if (!$this->inputNode) {
      self::echoError('Element name not found');
      return false;
    }

    return true;
  }

  private function getTargetLink() {
    self::echoDefault('Enter admin address for data source e.g. "/users": ');
    $this->targetLink = trim(fgets(STDIN));

    if (!$this->targetLink) {
      self::echoError('Invalid address for data source');
      return false;
    }

    if (!$this->loadAdminRoutes()) {
      self::echoError('Invalid routing config');
      return false;
    }

    if (!Arrays::getValueByPath($this->adminRoutes, 'routes/admin' . $this->targetLink)) {
      self::echoError('Supplied address does not exists');
      return false;
    }

    return true;
  }

  private function modifyNode() {
    $this->inputNode->setAttribute('aria-describedby', $this->elementName . '-selector');

    $parentNode = $this->inputNode->parentNode;

    // Create wrapper: <div class="input-group">
    $inputGroup = $this->dom->createElement('div');
    $inputGroup->setAttribute('class', 'input-group');

    $link = $this->webroot . '/admin' . $this->targetLink . '?slim_table=1&target=' . $this->elementName . '-id';

    $button = $this->dom->createElement('div');
    $button->setAttribute('class', 'input-group-text slim-selector-trigger');
    $button->setAttribute('id', $this->elementName . '-selector');
    $button->setAttribute('data-source', $link);

    // insert input-group before the original input
    $parentNode->insertBefore($inputGroup, $this->inputNode);

    // move the original input inside the input-group
    $inputGroup->appendChild($this->inputNode);

    // add button after the input
    $inputGroup->appendChild($button);

    $icon = $this->dom->createElement('i');
    $icon->setAttribute('class', 'bi bi-hand-index-fill');
    $icon->setAttribute('data-source', $link);
    $button->appendChild($icon);

    return true;
  }

  private function saveHtml() {
    $updatedHtml = $this->dom->saveHTML();

    $updatedHtml = str_replace('&lt;?', '<?', $updatedHtml);
    $updatedHtml = str_replace('?&gt;', '?>', $updatedHtml);

    file_put_contents(Config::get('app/paths/views') . '/admin/en/' . $this->templatePath, $updatedHtml);
  }

  private function loadAdminRoutes() {
    $config = file_get_contents(APP_ROOT . DS . 'app' . DS . 'Config' . DS . 'routes' . DS . 'admin' . DS . 'authenticated.json');

    if (!($this->adminRoutes = json_decode($config, true)) || json_last_error() != JSON_ERROR_NONE) {
      self::echoError(json_last_error_msg());
      return false;
    }

    return true;
  }
}