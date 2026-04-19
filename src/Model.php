<?php

/**
 * Parent class for all models
 * Populates the <code>$db</code> - database connection property
 */

namespace KrisRo\PhpRepublic;

use \KrisRo\PhpConfig\Config;

class Model {

  protected $db = null;

  public function __construct() {
    $this->db = Config::dbModel();
  }
}