<?php

namespace App\Actions\Users;

use App\Controllers\Users as UsersController;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Listing;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpConfig\Config;

class Tokens extends UsersController {

  private $filters = [
    'id' => ['id', []],
    'label' => ['user_tokens.label', []],
    'fingerprint' => ['fingerprint', []],
    'expires' => ['expires', []],
    'created' => ['created', []],
    'revoked' => ['revoked', []],
  ];

  public function run(): string {
    Config::set('css/tokens', 'tokens.css');

    $list = new Listing([
      'order-by' => 'id',
      'size' => '10',
      'sort' => 'asc',
    ], $this->filters);

    $list->select(['`user_tokens`.*'])
         ->from('user_tokens')
         ->join('users.id', 'user_tokens.user_id')
         ->filter('user_tokens.id', 'id')
         ->filter('user_tokens.label', 'label', 'LIKE')
         ->filter('user_tokens.fingerprint', 'fingerprint', 'LIKE')
         ->filter('user_tokens.revoked', 'revoked')
         ->filter('user_tokens.expires', 'expires', '=', 'date')
         ->filter('user_tokens.created', 'created', '=', 'date');

    $listData = $list->getData();

    $tokens = [];
    foreach ($listData['data'] as $token) {
      $tokens[$token['id']] = [
        'created' => Dates::format($token['created']),
        'expires' => Dates::format($token['expires']),
      ] + $token;
    }

    return Template::renderView(DS . SECTION . DS . Session::language() . '/users/user_tokens.php', [
      'tokens' => $tokens,
      'errors' => Messages::profileform_error(),
    ]);
  }
}