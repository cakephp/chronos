<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

class MutableDateTime extends DateTime implements ChronosInterface
{
    use DateTimeTrait;

    /**
     * Create a new MutableDateTime instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string $time Fixed or relative time
     * @param DateTimeZone|string $tz The timezone for the instance
     */
    public function __construct($time = null, $tz = null)
    {
        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        if (static::$testNow === null) {
            return parent::__construct($time, $tz);
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            return parent::__construct($time, $tz);
        }

        $testInstance = static::getTestNow();
        if ($relative) {
            $testInstance = clone $testInstance;
            $testInstance = $testInstance->modify($time);
        }

        if ($tz !== $testInstance->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz);
        }

        $time = $testInstance->format('Y-m-d H:i:s.u');
        parent::__construct($time, $tz);
    }

    /**
     * Create a new immutable instance from current mutable instance.
     *
     * @return Chronos
     */
    public function toImmutable()
    {
        return Chronos::instance($this);
    }

    /**
     * Set a part of the ChronosInterface object
     *
     * @param string $name The property to set.
     * @param string|int|DateTimeZone $value The value to set.
     * @throws InvalidArgumentException
     * @return void
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'year':
                $this->year($value);
                break;

            case 'month':
                $this->month($value);
                break;

            case 'day':
                $this->day($value);
                break;

            case 'hour':
                $this->hour($value);
                break;

            case 'minute':
                $this->minute($value);
                break;

            case 'second':
                $this->second($value);
                break;

            case 'timestamp':
                $this->timestamp($value);
                break;

            case 'timezone':
            case 'tz':
                $this->timezone($value);
                break;

            default:
                throw new InvalidArgumentException(sprintf("Unknown setter '%s'", $name));
        }
    }
}
