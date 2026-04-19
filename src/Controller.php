<?php

/**
 * Main controller class
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpRepublic\Session;
use KrisRo\PhpConfig\Config;

abstract class Controller implements \KrisRo\PhpRepublic\Interfaces\Controller {

  protected $sectionName = null;

  /**
   * Import mail from past requests
   */
  public function __construct() {
    $this->sectionName = SECTION;

    $this->importSendMessages();
  }

  public function run(): string {
    return 'Action must extend \KrisRo\PhpRepublic\Controller';
  }

  private function importSendMessages() {
    if (Session::get('send_error/1') == Config::current_page()) {
      Messages::popup_error(Session::get('send_error/0'));
      Session::set('send_error', null);
    }

    if (Session::get('send_warning/1') == Config::current_page()) {
      Messages::popup_warning(Session::get('send_warning/0'));
      Session::set('send_warning', null);
    }

    if (Session::get('send_info/1') == Config::current_page()) {
      Messages::popup_info(Session::get('send_info/0'));
      Session::set('send_info', null);
    }

    if (Session::get('send_success/1') == Config::current_page()) {
      Messages::popup_success(Session::get('send_success/0'));
      Session::set('send_success', null);
    }
  }
}
