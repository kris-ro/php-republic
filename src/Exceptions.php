<?php

/**
 * Exceptions handler
 */

namespace KrisRo\PhpRepublic;

class Exceptions {

  use \KrisRo\PhpRepublic\Traits\Logger;

  public static function install(): void {
    set_error_handler([Exceptions::class, 'handleAllErrors']);
  }

  /**
   * Handle exceptions
   *
   * @param int $errno
   * @param string $errstr
   * @param string|null $errfile
   * @param int|null $errline
   * @param array|null $errcontext
   * @return void
   */
  public static function handleAllErrors(int $errno, string $errstr, ?string $errfile = null, ?int $errline = 0, ?array $errcontext = []): void {
    $logger = static::getLogger();

    $message = $errstr;
    if ($errfile) {
      $message .= PHP_EOL . " > {$errfile} :: {$errline}";
    }

    switch ($errno) {
      case E_ERROR:
      case E_USER_ERROR:
      case E_RECOVERABLE_ERROR:
      case E_COMPILE_ERROR:
      case E_CORE_ERROR:
      case E_WARNING:
      case E_CORE_WARNING:
      case E_COMPILE_WARNING:
      case E_USER_WARNING:
      case E_NOTICE:
      case E_USER_NOTICE:
        $logger->error($errstr);
        if ($errfile) {
          $logger->error(" > {$errfile} :: {$errline}");
        }
      default:
        $logger->info($errstr);
        if ($errfile) {
          $logger->info(" > {$errfile} :: {$errline}");
        }
    }
  }
}
