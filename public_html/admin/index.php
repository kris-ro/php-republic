<?php

/**
 * This is the entry point for the admin section of the app.
 * Every admin request is routed to admin/index.php by .htaccess redirect rules
 */

use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Request;

define('APP_ROOT', dirname(__DIR__, 2));

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
  define('DS', '\\');
} else {
  define('DS', '/');
}

set_include_path(get_include_path() . PATH_SEPARATOR . APP_ROOT);

spl_autoload_extensions('.php');
spl_autoload_register();

define('SECTION', 'admin');

require_once APP_ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Loading...
 */
$bootstrap = new \KrisRo\PhpRepublic\Bootstrap();
$bootstrap->Web();

/**
 * Processing request
 */
$request = new Request();
$request->processPost();
$request->run();

/**
 * Printing result
 */
echo Template::layout();

/**
 * Shooting down
 */
$bootstrap->shutDown();