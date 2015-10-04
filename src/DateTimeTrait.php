<?php
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
namespace Cake\Chronos;

use DatePeriod;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * A simple API extension for DateTimeInterface
 */
trait DateTimeTrait
{
    use ComparisonTrait;
    use DifferenceTrait;
    use FactoryTrait;
    use FormattingTrait;
    use ModifierTrait;
    use TimezoneTrait;
    use TestingAidTrait;

    /**
     * Terms used to detect if a time passed is a relative date for testing purposes
     *
     * @var array
     */
    protected static $relativeKeywords = [
        'this',
        'next',
        'last',
        'tomorrow',
        'yesterday',
        '+',
        '-',
        'first',
        'last',
        'ago',
    ];

    /**
     * Get a part of the ChronosInterface object
     *
     * @param string $name The property name to read.
     * @return string|int|DateTimeZone The property value.
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        switch (true) {
            case array_key_exists($name, $formats = [
                'year' => 'Y',
                'yearIso' => 'o',
                'month' => 'n',
                'day' => 'j',
                'hour' => 'G',
                'minute' => 'i',
                'second' => 's',
                'micro' => 'u',
                'dayOfWeek' => 'w',
                'dayOfYear' => 'z',
                'weekOfYear' => 'W',
                'daysInMonth' => 't',
                'timestamp' => 'U',
            ]):
                return (int)$this->format($formats[$name]);

            case $name === 'weekOfMonth':
                return (int)ceil($this->day / ChronosInterface::DAYS_PER_WEEK);

            case $name === 'age':
                return (int)$this->diffInYears();

            case $name === 'quarter':
                return (int)ceil($this->month / 3);

            case $name === 'offset':
                return $this->getOffset();

            case $name === 'offsetHours':
                return $this->getOffset() / ChronosInterface::SECONDS_PER_MINUTE / ChronosInterface::MINUTES_PER_HOUR;

            case $name === 'dst':
                return $this->format('I') == '1';

            case $name === 'local':
                return $this->offset == $this->copy()->setTimezone(date_default_timezone_get())->offset;

            case $name === 'utc':
                return $this->offset == 0;

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
    public function __isset($name)
    {
        try {
            $this->__get($name);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Determine if there is a relative keyword in the time string, this is to
     * create dates relative to now for test instances. e.g.: next tuesday
     *
     * @param string $time The time string to check.
     * @return bool true if there is a keyword, otherwise false
     */
    public static function hasRelativeKeywords($time)
    {
        // skip common format with a '-' in it
        if (preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1) {
            foreach (static::$relativeKeywords as $keyword) {
                if (stripos($time, $keyword) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if instance of ChronosInterface is mutable.
     *
     * @return bool
     */
    public function isMutable()
    {
        return $this instanceof DateTime;
    }
}
