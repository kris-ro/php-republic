<?php

namespace App\Models;

use KrisRo\PhpRepublic\Session;

class UserToken extends \KrisRo\PhpRepublic\Model {

  public function deleteAll(int $id) {
    return $this->db->deleteUser_tokensByUser_id($id);
  }

  public function setToken(array $token): int {
    return $this->db->setUser_tokens($token);
  }

  public function getToken(int $id): array|false|null {
    $criteria = [
      'condition' => '`id` = :id AND `user_id` = :user_id',
      'params' => [
        ':id' => $id,
        ':user_id' => Session::get('user/id'),
      ],
    ];

    return $this->db->getAssocUser_tokensByCondition($criteria)->next();
  }

  public function getTokenByLabel(string $label): array|false|null {
    $criteria = [
      'condition' => '`label` LIKE :label AND `user_id` = :user_id',
      'params' => [
        ':label' => $label,
        ':user_id' => Session::get('user/id'),
      ],
    ];

    return $this->db->getAssocUser_tokensByCondition($criteria)->next();
  }

  public function getTokenByFingerprint(string $token, int $userId): array|false|null {
    $criteria = [
      'condition' => '`fingerprint` = :fingerprint AND `user_id` = :user_id',
      'params' => [
        ':fingerprint' => hash('sha256', $token),
        ':user_id' => $userId,
      ],
    ];

    return $this->db->getAssocUser_tokensByCondition($criteria)->next();
  }

  public function deleteToken(int $id) {
    $criteria = [
      'condition' => '`id` = :id AND `user_id` = :user_id',
      'params' => [
        ':id' => $id,
        ':user_id' => Session::get('user/id'),
      ],
    ];

    return $this->db->deleteUser_tokensByCondition($criteria);
  }
}