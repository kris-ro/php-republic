<?php

/**
 * This is the entry point for the API section of the app.
 * Every API request is routed to api/index.php by .htaccess redirect rules
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

define('SECTION', 'api');

require_once APP_ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Loading...
 */
$bootstrap = new \KrisRo\PhpRepublic\Bootstrap();
$bootstrap->Web();

/**
 * Shutdown if requested content type is not accepted
 */
if (!$bootstrap->AcceptedContentType()) {
  $bootstrap->shutDown();
  exit;
}

/**
 * Processing request
 */
$request = new Request();
$request->processPost();
$request->run();

/**
 * Set content type header
 */
$request->setContentTypeHeader();

/**
 * Printing result
 */
echo Template::page();

/**
 * Shooting down
 */
$bootstrap->shutDown();