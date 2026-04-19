<?php

/**
 * The (text interface) command processor
 *
 * Every command from `php cron.php -arg ...` is processed here
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpRepublic\Traits\ConsoleIO;

class Console implements \KrisRo\PhpRepublic\Interfaces\Console {

  use ConsoleIO;

  /**
   * Execute command
   */
  public function __construct() {
    $this->getAction();
  }
}
