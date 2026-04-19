<?php

namespace KrisRo\PhpRepublic\Interfaces;

interface Bootstrap {
  public function Web(): void;
  public function Console(): void;
}