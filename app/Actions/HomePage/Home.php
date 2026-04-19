<?php

namespace App\Actions\HomePage;

use App\Controllers\HomePage as HomeController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Request;

class Home extends HomeController {

  public function run(): string {
    return Template::renderView('/front/' . Session::language() . '/homepage/home.php', ['no_side_bar' => Request::get('no_side_bar')]);
  }
}