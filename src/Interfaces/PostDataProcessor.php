<?php

namespace KrisRo\PhpRepublic\Interfaces;

interface PostDataProcessor {
  public static function ValidatePostData(): void;
  public static function ProcessPostData(): void;
}