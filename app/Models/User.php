<?php

namespace App\Models;

use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;
use App\Models\UserToken;

class User extends \KrisRo\PhpRepublic\Model {

  public function setUser(array $user): int {
    $hash = Authenticate::hashPassword($user['password']);

    return $this->db->setUsersAndGetId([
      'email' => $user['email'],
      'password' => $hash,
      'username' => $user['username'],
      'phone' => $user['phone'] ?? '',
      'is_active' => $user['is_active'] ?? 0,
      'role' => $user['role'] ?? 'guest',
      'email_confirmed' => $user['email_confirmed'] ?? 0,
    ]);
  }

  public function updateUser(array $user) {
    $criteria = [
      'condition' => '`id` = :id',
      'params' => [':id' => $user['id']],
      'values' => [
        'email' => $user['email'],
        'username' => $user['username'],
        'phone' => $user['phone'] ?? '',
        'is_active' => $user['is_active'] ?? 0,
        'role' => $user['role'] ?? 'guest',
        'email_confirmed' => $user['email_confirmed'] ?? 0,
      ],
    ];

    if ($user['password'] ?? null) {
      $criteria['values']['password'] = Authenticate::hashPassword($user['password']);
    }

    return $this->db->updateUsersByCondition($criteria);
  }

  public function updateLastLogin(array $user) {
    $criteria = [
      'condition' => '`id` = :id',
      'params' => [':id' => $user['id']],
      'values' => [
        'last_login' => $user['last_login'],
      ],
    ];

    return $this->db->updateUsersByCondition($criteria);
  }

  public function getUserByUserId(int $id): array|null {
    return $this->getUser($id);
  }

  public function getUserById(int $id): array|null {
    return $this->getUser($id);
  }

  public function getUserByEmail(string $email): array|null {
    return $this->getUser($email);
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

  public function getUserConfirmationCode(array $user) {
    return sha1($user['email']) . sha1((string) $user['password']);
  }

  public function validateEmailAddress(string $code) {
    $criteria = [
      'condition' => 'CONCAT(SHA1(`email`), SHA1(COALESCE(`password`, ""))) = :code',
      'params' => [
        ':code' => $code,
      ],
    ];

    if (!($user = $this->db->getAssocUsersByCondition($criteria)->next() ?: null)) {
      return false;
    }

    $user['is_active'] = 1;
    $user['email_confirmed'] = 1;
    unset($user['password']);

    return $this->updateUser($user);
  }
}
