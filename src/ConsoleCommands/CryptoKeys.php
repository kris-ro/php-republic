<?php

namespace KrisRo\PhpRepublic\ConsoleCommands;

use KrisRo\PhpRepublic\Crypto;
use KrisRo\PhpRepublic\Traits\ConsoleIO;

class CryptoKeys {

  use ConsoleIO;

  public function __construct(?string $type = '') {
    switch (strtolower($type)) {
      case 'iv':
        self::echoInfo('IV string bellow');
        echo Crypto::generateOpenSslIv() . PHP_EOL;
        break;
      case 'key':
        self::echoInfo('Base64 encoded key bellow');
        echo Crypto::generateEncryptionKey() . PHP_EOL;
        break;
      default:
        self::echoError('No parameters provided. Correct command: [php cron.php|./republic] cryptoKeys --type=[iv|key]');
    }
  }
}
