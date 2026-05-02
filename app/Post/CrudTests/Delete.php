<?php

namespace App\Post\CrudTests;

use KrisRo\PhpConfig\Config;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Session;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Interfaces\PostDataProcessor;
use KrisRo\PhpRepublic\Traits\CSRF;

class Delete implements PostDataProcessor {

  use CSRF;

  static protected $formId = 'deletecrud_test';

  public static function ValidatePostData(): void {
    $result = Config::validator()
              ->addPostValidationMessages([
                 self::INPUT_ELEMENT_NAME => Translate::csrf('Invalid Form'),
                'crud_test_id' => Translate::crud_test('Invalid crud test id'),
              ])
              ->addPostValidationRules([
                 self::INPUT_ELEMENT_NAME => [['App\\Post\\CrudTests\\Delete', 'validFormToken']],
                'crud_test_id' => ['positiveInteger', ['between', 'lowerLimit' => 0, 'upperLimit' => 4294967295]],
              ])
              ->processPost();

    if (!$result) {
      Messages::deletecrud_testform_error(Config::validator()->getPostValidationMessages());
    }
  }

  public static function ProcessPostData(): void {
    if (!empty(Config::validator()->getPostValidationMessages())) {
      return;
    }

    (new \App\Models\CrudTest())->deleteCrudTestByCrudTestId(Config::validator()->getPost()['crud_test_id']);

    Session::set('request/messages/crudtests/popup_success', Translate::crud_test('Crud test was deleted'));

    Request::redirect('/admin/crudtests');
  }

}


