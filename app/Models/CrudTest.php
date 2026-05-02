<?php

namespace App\Models;

use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;

class CrudTest extends \KrisRo\PhpRepublic\Model {

  public function setCrudTest(array $data): int {
    return $this->db->setCrud_test([
      'email' =>  $data['email'],
      'price' =>  $data['price'],
      'timestamp_time' =>  $data['timestamp_time'],
      'date_time_field' =>  $data['date_time_field'],
      'date_field' =>  $data['date_field'],
      'enum_field' =>  $data['enum_field'],
      'boolean_field' =>  $data['boolean_field'],
      'long_blob_field' =>  $data['long_blob_field'],
      'long_text_field' =>  $data['long_text_field'],
      'small_int_field' =>  $data['small_int_field'],
      'uuid_field' =>  $data['uuid_field'],
      'default_null_value' =>  $data['default_null_value'],
      'time_field' =>  $data['time_field'],
      'default_empty_string' =>  $data['default_empty_string'],
    ]);
  }

  public function updateCrudTestByCrudTestId(array $data) {
    $criteria = [
      'condition' => '`crud_test_id` = :crud_test_id',
      'params' => [':crud_test_id' => $data['crud_test_id']],
      'values' => [
        'email' =>  $data['email'],
        'price' =>  $data['price'],
        'timestamp_time' =>  $data['timestamp_time'],
        'date_time_field' =>  $data['date_time_field'],
        'date_field' =>  $data['date_field'],
        'enum_field' =>  $data['enum_field'],
        'boolean_field' =>  $data['boolean_field'],
        'long_blob_field' =>  $data['long_blob_field'],
        'long_text_field' =>  $data['long_text_field'],
        'small_int_field' =>  $data['small_int_field'],
        'uuid_field' =>  $data['uuid_field'],
        'default_null_value' =>  $data['default_null_value'],
        'time_field' =>  $data['time_field'],
        'default_empty_string' =>  $data['default_empty_string'],
      ],
    ];

    return $this->db->updateCrud_testByCondition($criteria);
  }

  public function updateCrudTestByUuidField(array $data) {
    $criteria = [
      'condition' => '`uuid_field` = :uuid_field',
      'params' => [':uuid_field' => $data['uuid_field']],
      'values' => [
        'email' =>  $data['email'],
        'price' =>  $data['price'],
        'timestamp_time' =>  $data['timestamp_time'],
        'date_time_field' =>  $data['date_time_field'],
        'date_field' =>  $data['date_field'],
        'enum_field' =>  $data['enum_field'],
        'boolean_field' =>  $data['boolean_field'],
        'long_blob_field' =>  $data['long_blob_field'],
        'long_text_field' =>  $data['long_text_field'],
        'small_int_field' =>  $data['small_int_field'],
        'default_null_value' =>  $data['default_null_value'],
        'time_field' =>  $data['time_field'],
        'default_empty_string' =>  $data['default_empty_string'],
      ],
    ];

    return $this->db->updateCrud_testByCondition($criteria);
  }

  public function getCrudTestByCrudTestId($value): array|null {
    return $this->db->getAssocCrud_testByCrud_test_id($value)->next() ?: null;
  }

  public function deleteCrudTestByCrudTestId($value) {
    return $this->db->deleteCrud_testByCrud_test_id($value);
  }

  public function getCrudTestByUuidField($value): array|null {
    return $this->db->getAssocCrud_testByUuid_field($value)->next() ?: null;
  }

  public function deleteCrudTestByUuidField($value) {
    return $this->db->deleteCrud_testByUuid_field($value);
  }

}

