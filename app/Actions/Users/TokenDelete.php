<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Template;
use App\Models\UserToken;
use KrisRo\PhpConfig\Config;

class TokenDelete extends UsersController {

  public function run(): string {
    Config::set('css/delete', 'profile.css');

    $tokenId = Request::nth(6);
    if (!(Config::validator()->positiveInteger($tokenId)) || !($token = (new UserToken())->getToken($tokenId))) {
      Messages::send_popup('Invalid Token ID');
      Request::redirect('/admin/account/user/tokens');
    }

    return Template::renderView(DS . SECTION . DS . Session::language() . '/users/token_delete.php', [
      'token' => $token,
      'errors' => Messages::deletetokenform_error(),
    ]);
  }
}