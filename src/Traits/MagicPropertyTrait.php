<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice. Provides various operator methods for datetime
 * objects.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Traits;

use Cake\Chronos\ChronosInterface;
use InvalidArgumentException;

/**
 * Provides the magic methods that allow read access
 * to magic properties.
 *
 * @property-read int $year
 * @property-read int $yearIso
 * @property-read int $month
 * @property-read int $day
 * @property-read int $hour
 * @property-read int $minute
 * @property-read int $second
 * @property-read int $micro
 * @property-read int $dayOfWeek
 * @property-read int $dayOfYear
 * @property-read int $weekOfYear
 * @property-read int $daysInMonth
 * @property-read int $timestamp
 * @property-read int $weekOfMonth
 * @property-read int $age
 * @property-read int $quarter
 * @property-read int $offset
 * @property-read int $offsetHours
 * @property-read bool $dst
 * @property-read bool $local
 * @property-read bool $utc
 * @property-read \DateTimeZone $timezone
 * @property-read \DateTimeZone $tz
 * @property-read string $timezoneName
 * @property-read string $tzName
 * @property-read string $dayOfWeekName
 */
trait MagicPropertyTrait
{
    /**
     * Get a part of the ChronosInterface object
     *
     * @param string $name The property name to read.
     * @return string|int|bool|\DateTimeZone The property value.
     * @throws \InvalidArgumentException
     */
    public function __get(string $name)
    {
        static $formats = [
            'year' => 'Y',
            'yearIso' => 'o',
            'month' => 'n',
            'day' => 'j',
            'hour' => 'G',
            'minute' => 'i',
            'second' => 's',
            'micro' => 'u',
            'microsecond' => 'u',
            'dayOfWeek' => 'N',
            'dayOfYear' => 'z',
            'weekOfYear' => 'W',
            'daysInMonth' => 't',
            'timestamp' => 'U',
        ];

        switch (true) {
            case isset($formats[$name]):
                return (int)$this->format($formats[$name]);

            case $name === 'dayOfWeekName':
                return $this->format('l');

            case $name === 'weekOfMonth':
                return (int)ceil($this->day / ChronosInterface::DAYS_PER_WEEK);

            case $name === 'age':
                return $this->diffInYears();

            case $name === 'quarter':
                return (int)ceil($this->month / 3);

            case $name === 'offset':
                return $this->getOffset();

            case $name === 'offsetHours':
                return (int)($this->getOffset() /
                    ChronosInterface::SECONDS_PER_MINUTE / ChronosInterface::MINUTES_PER_HOUR);

            case $name === 'dst':
                return $this->format('I') === '1';

            case $name === 'local':
                return $this->offset === $this->copy()->setTimezone(date_default_timezone_get())->offset;

            case $name === 'utc':
                return $this->offset === 0;

            case $name === 'timezone' || $name === 'tz':
                return $this->getTimezone();

            case $name === 'timezoneName' || $name === 'tzName':
                return $this->getTimezone()->getName();

            default:
                throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $name));
        }
    }

    /**
     * Check if an attribute exists on the object
     *
     * @param string $name The property name to check.
     * @return bool Whether or not the property exists.
     */
    public function __isset(string $name): bool
    {
        try {
            $this->__get($name);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }
}
