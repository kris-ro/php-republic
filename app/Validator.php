<?php

namespace App;

class Validator extends \KrisRo\Validator\Validator {
  public function validUserId($value) {
    $id = current(explode('|', $value ?? '') ?: []);

    return !empty((new UserModel())->getUser(intval($id)));
  }

  public function validUserEmail($value) {
    if (empty($value)) {
      return false;
    }

    return !empty((new UserModel())->getUser((string) $value));
  }

  public function phone($value) {
    $phone = preg_replace('/[^0-9]/', '', $value);
    
    if (strlen($phone) < 10) {
      return false;
    }

    return true;
  }

  public function validRole($value) {
    if (in_array($value, \App\Models\User::getRoles([\App\Models\User::ROLE_GUEST]))) {
      return true;
    }

    return false;
  }

  // DO NOT DELETE THIS LINE //
}
