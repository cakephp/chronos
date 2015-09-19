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

class Carbon extends DateTime implements CarbonInterface
{
    use CarbonTrait;

    /**
     * Create a new Carbon instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string              $time
     * @param DateTimeZone|string $tz
     */
    public function __construct($time = null, $tz = null)
    {
        // If the class has a test now set and we are trying to create a now()
        // instance then override as required
        if (static::hasTestNow() && (empty($time) || $time === 'now' || static::hasRelativeKeywords($time))) {
            $testInstance = clone static::getTestNow();
            if (static::hasRelativeKeywords($time)) {
                $testInstance->modify($time);
            }

            //shift the time according to the given time zone
            if ($tz !== NULL && $tz != static::getTestNow()->tz) {
                $testInstance->setTimezone($tz);
            } else {
                $tz = $testInstance->tz;
            }

            $time = $testInstance->toDateTimeString();
        }

        parent::__construct($time, static::safeCreateDateTimeZone($tz));
    }

    /**
     * Create a new immutable instance from current mutable instance.
     *
     * @return CarbonImmutable
     */
    public function toImmutable()
    {
        return CarbonImmutable::instance($this);
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
