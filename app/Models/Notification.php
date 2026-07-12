<?php

namespace App\Models;

use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;
use App\Models\UserToken;

class Notification extends \KrisRo\PhpRepublic\Model {

  public static function getStructure($data = []) {
    return $data + [
      'notifications_id' => null,
      'subject' => '',
      'body' => '',
      'users_id' => '',
      'email' => '',
      'from' => '',
      'created' => null,
    ];
  }
}