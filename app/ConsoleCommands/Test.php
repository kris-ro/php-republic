<?php

namespace App\ConsoleCommands;

class Test {

  public function __construct(int|null $first = null, string|null $second = null) {
    print __METHOD__;
    print PHP_EOL;

    if ($first ?? null) {
      print $first;
      print PHP_EOL;
    }

    if ($second ?? null) {
      print $second;
      print PHP_EOL;
    }
  }

  public static function staticTest(int $first, string $second) {
    print __METHOD__;
    print PHP_EOL;

    if ($first ?? null) {
      print $first;
      print PHP_EOL;
    }

    if ($second ?? null) {
      print $second;
      print PHP_EOL;
    }
  }

  public static function instanceTest() {
    print __METHOD__;
    print PHP_EOL;

    if ($argv[0] ?? null) {
      print $argv[0];
      print PHP_EOL;
    }

    if ($argv[1] ?? null) {
      print $argv[12];
      print PHP_EOL;
    }

    if ($argv[2] ?? null) {
      print $argv[12];
      print PHP_EOL;
    }
  }
}
