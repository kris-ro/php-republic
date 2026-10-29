<?php

namespace App\Helpers;

use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Mailer;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpConfig\Config;

use App\Models\User as UserModel;

class Emails {

  public static function SendNewAccountValidationEmail(int $userId) {
    $userModel = new UserModel();

    $user = $userModel->getUser($userId);

    $body = Template::renderView('/front/' . Session::language() . '/users/_emails/new_account_validation_email.php', [
      'user' => $user,
      'confirmation_code' => $userModel->getUserConfirmationCode($user),
      'webroot' => Config::get('url/protocol') . '://' . Config::get('url/domain'),
    ]);

    $message = [
      'confirm' => Translate::users('The account details were sent to the provided email.'),
      'subject' => Translate::users('Validate your account on __DOMAIN__', ['__DOMAIN__' => Config::get('url/domain')]),
      'message' => $body,
      'to' => [
        'address' => $user['email'],
        'name' => $user['username'],
      ],
      'from' => [
        'address' => Config::get('mail/contact'),
        'name' => Config::get('mail/signature'),
      ],
      'reply_to' => [
        'address' => Config::get('mail/support'),
      ],
      'skip_notification' => false,
    ];

    return Mailer::send($message, false, Session::language() ?? Config::get('app/default_language'));
  }

  public static function SendForgotPasswordEmail(int $userId) {
    $userModel = new UserModel();

    $user = $userModel->getUser($userId);

    $body = Template::renderView('/front/' . Session::language() . '/users/_emails/forgot_password_email.php', [
      'user' => $user,
      'token' => (new \App\Models\RecoveryToken())->getRecoveryPasswordCode($user),
      'webroot' => Config::get('url/protocol') . '://' . Config::get('url/domain'),
    ]);

    $message = [
      'confirm' => Translate::users('Recovery instructions were sent to the provided email.'),
      'subject' => Translate::users('Recover your password on __DOMAIN__', ['__DOMAIN__' => Config::get('url/domain')]),
      'message' => $body,
      'to' => [
        'address' => $user['email'],
        'name' => $user['username'],
      ],
      'from' => [
        'address' => Config::get('mail/contact'),
        'name' => Config::get('mail/signature'),
      ],
      'reply_to' => [
        'address' => Config::get('mail/support'),
      ],
      'skip_notification' => true,
    ];

    return Mailer::send($message, false, Session::language() ?? Config::get('app/default_language'));
  }
}
