<?php

namespace App\Models;

use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;

class Todo extends \KrisRo\PhpRepublic\Model {

  public function setTodo(array $data): int {
    return $this->db->setTodo([
      'title' =>  $data['title'],
      'details' =>  $data['details'],
      'status' =>  $data['status'],
      'users_id' =>  $data['users_id'],
      'created' =>  $data['created'],
      'updated' =>  $data['updated'],
    ]);
  }

  public function updateTodoByTodoId(array $data) {
    $criteria = [
      'condition' => '`todo_id` = :todo_id',
      'params' => [':todo_id' => $data['todo_id']],
      'values' => [
        'title' =>  $data['title'],
        'details' =>  $data['details'],
        'status' =>  $data['status'],
        'users_id' =>  $data['users_id'],
        'created' =>  $data['created'],
        'updated' =>  $data['updated'],
      ],
    ];

    return $this->db->updateTodoByCondition($criteria);
  }

  public function getTodoByTodoId($value): array|null {
    return $this->db->getAssocTodoByTodo_id($value)->next() ?: null;
  }

  public function deleteTodoByTodoId($value) {
    return $this->db->deleteTodoByTodo_id($value);
  }

}

