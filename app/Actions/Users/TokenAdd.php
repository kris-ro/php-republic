<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Dates;

class TokenAdd extends UsersController {

  public function run(): string {
    Config::set('css/' . Config::get('current_page'), 'profile.css');
    Config::set('js/' . Config::get('current_page'), 'token-add.js');

    $criteria = [
      'condition' => '`revoked` = :revoked AND `expires` > :expires',
      'params' => [
        ':revoked' => 0,
        ':expires' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
      ],
      'range' => '0,15',
      'order' => '`expires` DESC',
    ];

    $tokens = Config::dbModel()->getAssocUser_tokensByCondition($criteria)->all();

    foreach ($tokens ?: [] as $key => $item) {
      $tokens[$key]['created'] = Dates::format($item['created']);
      $tokens[$key]['expires'] = Dates::format($item['expires']);
    }

    return Template::renderView(DS . SECTION . DS . Session::language() . '/users/user_tokens.php', [
      'tokens' => $tokens,
      'token_add_form' => Template::renderView(DS . SECTION . DS . Session::language() . '/users/_partials/token_add_form.php', ['errors' => Messages::tokenaddform_error()]),
    ]);
  }
}