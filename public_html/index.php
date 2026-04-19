<?php

/**
 * This is the entry point for the frontend section of the app.
 * Every request is routed to index.php by .htaccess redirect rules
 */

use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Template;

define('APP_ROOT', dirname(__DIR__));

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
  define('DS', '\\');
} else {
  define('DS', '/');
}

set_include_path(get_include_path() . PATH_SEPARATOR . APP_ROOT);

spl_autoload_extensions('.php');
spl_autoload_register();

define('SECTION', 'front');

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