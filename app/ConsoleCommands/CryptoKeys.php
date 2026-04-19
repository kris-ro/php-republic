<?php

namespace App\ConsoleCommands;

use KrisRo\PhpRepublic\Crypto;

class CryptoKeys {

  public function __construct(string $type) {
    switch (strtolower($type)) {
      case 'iv':
        echo Crypto::generateOpenSslIv() . PHP_EOL;
        break;
      case 'key':
        echo Crypto::generateEncryptionKey() . PHP_EOL;
        break;
      default:
        echo PHP_EOL . PHP_EOL . PHP_EOL . 'No parameters provided. Correct command: [php cron.php|./republic] cryptoKeys --type=[iv|key]' . PHP_EOL;
    }
  }
}
