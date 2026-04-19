<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;

class Profile extends UsersController {

  public function run(): string {
    $this->{$this->sectionName}();

    return Template::renderView(DS . SECTION . DS . Session::language() . '/users/profile.php', [
      'user' => Session::user(),
      'left_user_summary' => (SECTION == 'admin') ? Template::renderView(DS . SECTION . DS . Session::language() . '/users/_partials/user_summary.php') : '',
      'errors' => Messages::profileform_error(),
    ]);
  }

  private function admin() {
    Config::set('css/profile', 'profile.css');
  }

  private function api() {}
}