<?php

/**
 * Handles Messages across the application. Use it like:
 * <code>
 *  // Setting messages
 *  \KrisRo\PhpRepublic\Messages::channel(['error-1' => 'Error', ...]);
 *  \KrisRo\PhpRepublic\Messages::some_other_id('Some error');
 *
 *  // Getting messages
 *  \KrisRo\PhpRepublic\Messages::channel();
 *  \KrisRo\PhpRepublic\Messages::some_other_id();
 * </code>
 *
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpRepublic\Session;

class Messages {
  const SUCCESS = 'success';
  const INFO = 'info';
  const WARNING = 'warning';
  const ERROR = 'error';

  private static $messages = [];

  /**
   * Handles getting and setting
   *
   * @param string $channel
   * @param array $arguments
   * @return array
   */
  public static function __callStatic(string $channel, array $arguments): array {
    $channel = strtolower((string) $channel);

    if (empty($arguments[0])) {
      return self::$messages[$channel] ?? [];
    }

    if (strpos($channel, 'send_') !== false) {
      Session::set($channel, $arguments);
    }

    return self::addMessages($channel, $arguments[0]);
	}

  /**
   * Get messages
   *
   * @param string|null $type
   *
   *
   * @return type
   */
  public static function getAllMessages(?string $type = null) {
    if (!$type) {
      return self::$messages;
    }

    if (!in_array($type, [self::SUCCESS, self::INFO, self::WARNING, self::ERROR])) {
      throw new Exception('Invalid Message Type');
    }

    $typeMessages = [];

    foreach (self::$messages as $key => $messages) {
      if (str_ends_with($key, $type)) {
        $typeMessages[$key] = $messages;
      }
    }

    return $typeMessages;
  }

  private static function addMessages(string $channel, string|array $messages): array {
    if (is_string($messages)) {
      self::$messages[$channel][] = $messages;
    }

    if (is_array($messages)) {
      foreach ($messages as $key => $message) {
        self::$messages[$channel][intval($key) != $key ? $key : null] = $message;
      }
    }

    return self::$messages[$channel] ?? [];
  }
}