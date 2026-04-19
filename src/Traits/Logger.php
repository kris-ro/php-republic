<?php

namespace KrisRo\PhpRepublic\Traits;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler as StreamHandler;
use Monolog\Handler\ErrorLogHandler as ErrorLogHandler;
use Monolog\Handler\NativeMailerHandler as NativeMailerHandler;
use Monolog\Formatter\LineFormatter;

use KrisRo\PhpConfig\Config;

trait Logger {
  public static function getLogger() {
    $logger = new MonologLogger('logger');

    $logger->pushHandler(new StreamHandler(APP_ROOT . DS . Config::get('app/paths/logs') . 'app.log', MonologLogger::DEBUG));

    $errorHandler = new ErrorLogHandler();
    $formatter = new LineFormatter();
    $formatter->includeStacktraces();
    $errorHandler->setFormatter($formatter);

    $logger->pushHandler($errorHandler);
    $logger->pushHandler(new NativeMailerHandler(Config::get('mail/system'), 'Logger', Config::get('mail/system')));

    return $logger;
  }
}