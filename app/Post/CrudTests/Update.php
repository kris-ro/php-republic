<?php

namespace App\Post\CrudTests;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Files;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Update implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'updatecrud_test';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'crud_test_id' => Translate::crud_test('Invalid crud test id'),
                'email' => Translate::crud_test('Invalid email'),
                'price' => Translate::crud_test('Invalid price'),
                'timestamp_time' => Translate::crud_test('Invalid timestamp time'),
                'date_time_field' => Translate::crud_test('Invalid date time field'),
                'date_field' => Translate::crud_test('Invalid date field'),
                'enum_field' => Translate::crud_test('Invalid enum field'),
                'boolean_field' => Translate::crud_test('Invalid boolean field'),
                'long_blob_field' => Translate::crud_test('Invalid long blob field'),
                'long_text_field' => Translate::crud_test('Invalid long text field'),
                'small_int_field' => Translate::crud_test('Invalid small int field'),
                'uuid_field' => Translate::crud_test('Invalid uuid field'),
                'default_null_value' => Translate::crud_test('Invalid default null value'),
                'time_field' => Translate::crud_test('Invalid time field'),
              ])
              ->addPostValidationRules([
                self::INPUT_ELEMENT_NAME => [['App\\Post\\CrudTests\\Update', 'validFormToken']],
                'crud_test_id' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 4294967295]],
                'email' => ['is_string', ['maxLength', 255]],
                'price' => ['float', ['smallerThan', PHP_FLOAT_MAX], 'isOptional'],
                'timestamp_time' => ['is_string', ['KrisRo\\PhpRepublic\\Dates', 'isValidMySqlDateTime'], 'isOptional'],
                'date_time_field' => ['is_string', ['KrisRo\\PhpRepublic\\Dates', 'isValidMySqlDateTime'], 'isOptional'],
                'date_field' => ['is_string', 'isValidDate', 'isOptional'],
                'enum_field' => ['is_string', [new Update(), 'validEnumField'], 'isOptional'],
                'boolean_field' => ['integer', ['between', 'lowerLimit' => -128, 'upperLimit' => 127]],
                'long_blob_field' => [[new Update(), 'validLongBlobField']],
                'long_text_field' => ['is_string', ['maxLength', 4294967295]],
                'small_int_field' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 65535]],
                'uuid_field' => [[new Update(), 'validUuidField']],
                'default_null_value' => ['is_string', ['maxLength', 255], 'isOptional'],
                'time_field' => ['is_string', ['KrisRo\\PhpRepublic\\Dates', 'isValidMySqlTime'], 'isOptional'],
              ])
              ->processPost();

    if (!$result) {
      Messages::updatecrud_testform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    (new \App\Models\CrudTest())->updateCrudTestByCrudTestId(Config::validator()->getPost());

    Session::set('request/messages/crudtests/popup_success', Translate::crud_test('Crud test was saved'));

    Request::redirect('/admin/crudtests');
  }

  public function validEnumField($value, $post) {
    $acceptedValues = ['aaa', 'bbb', 'ccc'];

    if (in_array($value, $acceptedValues)) {
      return true;
    }

    return false;
  }

  public function validLongBlobField($value, $post) {
    if (empty($_FILES) && empty($_POST)) {
      Config::validator()->setPostValidationMessage('long_blob_field', Translate::files('File is way to big. Max file size is @MAXFILEZISE', ['@MAXFILEZISE' => ini_get('upload_max_filesize')]));
      return false;
    }

    if (!($_FILES['long_blob_field']['tmp_name'] ?? null)) {
      Config::validator()->setPostValidationMessage('long_blob_field', Translate::files('No file was uploaded'));
      return false;
    }

    if (!isset($_FILES['long_blob_field']['error'])) {
      Config::validator()->setPostValidationMessage('long_blob_field', Translate::files('Unknown upload error'));
      return false;
    }

    if (!isset($_FILES['long_blob_field']['error']) || $_FILES['long_blob_field']['error'] != UPLOAD_ERR_OK) {
      Config::validator()->setPostValidationMessage('long_blob_field', Translate::files('Error: @UPLOAD_ERROR', ['@UPLOAD_ERROR' => Files::errorMessage($_FILES['long_blob_field']['error'])]));
      return false;
    }

    if (isset($_FILES['long_blob_field']['size']) && $_FILES['long_blob_field']['size'] == 0) {
      Config::validator()->setPostValidationMessage('long_blob_field', Translate::files('File is way to big. Max file size is @MAXFILEZISE', ['@MAXFILEZISE' => ini_get('upload_max_filesize')]));
      return false;
    }

    return true;
  }

  public function validUuidField($value, $post) {
    if (empty($_FILES) && empty($_POST)) {
      Config::validator()->setPostValidationMessage('uuid_field', Translate::files('File is way to big. Max file size is @MAXFILEZISE', ['@MAXFILEZISE' => ini_get('upload_max_filesize')]));
      return false;
    }

    if (!($_FILES['uuid_field']['tmp_name'] ?? null)) {
      Config::validator()->setPostValidationMessage('uuid_field', Translate::files('No file was uploaded'));
      return false;
    }

    if (!isset($_FILES['uuid_field']['error'])) {
      Config::validator()->setPostValidationMessage('uuid_field', Translate::files('Unknown upload error'));
      return false;
    }

    if (!isset($_FILES['uuid_field']['error']) || $_FILES['uuid_field']['error'] != UPLOAD_ERR_OK) {
      Config::validator()->setPostValidationMessage('uuid_field', Translate::files('Error: @UPLOAD_ERROR', ['@UPLOAD_ERROR' => Files::errorMessage($_FILES['uuid_field']['error'])]));
      return false;
    }

    if (isset($_FILES['uuid_field']['size']) && $_FILES['uuid_field']['size'] == 0) {
      Config::validator()->setPostValidationMessage('uuid_field', Translate::files('File is way to big. Max file size is @MAXFILEZISE', ['@MAXFILEZISE' => ini_get('upload_max_filesize')]));
      return false;
    }

    return true;
  }
}


