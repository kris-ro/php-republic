<?php

namespace App\Actions\Debug;

use App\Controllers\Debug as DebugController;

use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpRepublic\Request;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpConfig\Config;

class Emails extends DebugController {

  private $listSize = 25;

  public function run(): string {

    if (!Config::_debug()) {
      exit;
    }

    $dir = APP_ROOT . DS . 'app' . DS . 'mails';

    $email = null;
    if (Request::get('file') && file_exists($dir . '/' . Request::get('file'))) {
      $email = file_get_contents($dir . '/' . $_GET['file']);
    }

    $lastFiles = $this->scanDir($dir);

    return Template::renderView('/admin/' . Session::language() . '/debug/emails.php', [
      'files' => $lastFiles,
      'email' => $email,
    ]);
  }

  private function scanDir(string $dir) {
    if (!file_exists($dir)) {
      return [];
    }

    $ignored = ['.', '..'];

    $files = [];    
    foreach (scandir($dir) as $file) {
      if (in_array($file, $ignored)) {
        continue;
      }

      $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);

    $i = 0;
    foreach ($files as $file => $data) {
      if ($i < $this->listSize) {
        $i++;
      } else {
        unlink($dir . '/' . $file);
      }
    } 

    $files = array_keys($files);

    return $files;
  }
}