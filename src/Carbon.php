<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

class Carbon extends DateTime implements ChronosInterface
{
    use DateTimeTrait;

    /**
     * Create a new Carbon instance.
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
     * @return CarbonImmutable
     */
    public function toImmutable()
    {
        return Chronos::instance($this);
    }

    /**
     * Set a part of the CarbonInterface object
     *
     * @param string                      $name
     * @param string|integer|DateTimeZone $value
     *
     * @throws InvalidArgumentException
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'year':
                return $this->year($value);
                break;

            case 'month':
                return $this->month($value);
                break;

            case 'day':
                return $this->day($value);
                break;

            case 'hour':
                return $this->hour($value);
                break;

            case 'minute':
                return $this->minute($value);
                break;

            case 'second':
                return $this->second($value);
                break;

            case 'timestamp':
                return $this->timestamp($value);
                break;

            case 'timezone':
            case 'tz':
                return $this->timezone($value);
                break;

            default:
                throw new InvalidArgumentException(sprintf("Unknown setter '%s'", $name));
        }
    }
}
