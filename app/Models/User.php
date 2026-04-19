<?php

namespace App\Models;

use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;
use App\Models\UserToken;

class User extends \KrisRo\PhpRepublic\Model {

  public function setUser(array $user): int {
    return $this->db->setUsers([
      'email' => $user['email'],
      'password' => Authenticate::hashPassword($user['password']),
      'username' => $user['username'],
    ]);
  }

  public function updateUser(array $user) {
    $criteria = [
      'condition' => '`id` = :id',
      'params' => [':id' => $user['id']],
      'values' => [
        'email' => $user['email'],
        'username' => $user['username'],
      ],
    ];

    if ($user['password'] ?? null) {
      $criteria['values']['password'] = Authenticate::hashPassword($user['password']);
    }

    return $this->db->updateUsersByCondition($criteria);
  }

  public function getUser(int|string $id): array|null {
    // email
    if (is_string($id)) {
      return $this->db->getAssocUsersByEmail($id)->next() ?: null;
    }

    // user ID
    if (is_int($id)) {
      return $this->db->getAssocUsersById($id)->next() ?: null;
    }

    return null;
  }

  public function deleteUser(int $id) {
    if (!is_int((new UserToken())->deleteAll($id))) {
      Messages::popup_error(Translate::app('User tokens delete failed'));
      return false;
    }

    return $this->db->deleteUsersById($id);
  }
}