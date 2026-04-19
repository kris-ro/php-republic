<?php

namespace App\Actions\HomePage;

use App\Controllers\HomePage as HomeController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;

class Dashboard extends HomeController {

  public function run(): string {
    return Template::renderView('/admin/' . Session::language() . '/homepage/dashboard.php');
  }
}