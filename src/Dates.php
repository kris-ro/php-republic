<?php

/**
 * Dates and time processing
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;

class Dates {

  /**
   * Format a datetime
   *
   * @param int|string $dateTime
   * @param string|null $format
   * @param string|null $timeZone
   * @return string
   */
  public static function format(int|string $dateTime, ?string $format = null, ?string $timeZone = null): string {
    if (intval($dateTime) == $dateTime) {
      $dateTime = '@' . $dateTime;
    }

    if (!$format) {
      $format = Config::get('app/datetime/date_format');
    }

    if (!$timeZone) {
      $timeZone = Config::get('app/datetime/time_zone');
    }

    return (new \DateTime($dateTime))->setTimezone(new \DateTimeZone($timeZone))->format($format);
  }

  /**
   * Validate input for MySql datetime format
   *
   * @param string $dateTime
   *
   * @return bool
   */
  public static function isValidMySqlDateTime(string $dateTime): bool {
    $format = 'Y-m-d\TH:i:s';
    $sufix = '';

    if (!($date = \DateTime::createFromFormat($format, $dateTime))) {
      $sufix = ':00';
      $date = \DateTime::createFromFormat($format, $dateTime . $sufix);
    }

    return $date && $date->format($format) === $dateTime . $sufix;
  }

  /**
   * Validate input for MySql time format
   *
   * @param string $time
   *
   * @return bool
   */
  public static function isValidMySqlTime(string $time): bool {
    $format = 'H:i:s';
    $sufix = '';

    if (!($date = \DateTime::createFromFormat($format, $time))) {
      $sufix = ':00';
      $date = \DateTime::createFromFormat($format, $time . $sufix);
    }

    return $date && $date->format($format) === $time . $sufix;
  }

  public static function convertDateTimeToUTC(string $time, ?string $localZone = '') {
    if (!$localZone && !($localZone = Config::get('app/datetime/time_zone'))) {
      return $time;
    }

    $localTime = new \DateTime($time, new \DateTimeZone($localZone));

    $localTime->setTimezone(new \DateTimeZone('UTC'));

    return $localTime;
  }
}
