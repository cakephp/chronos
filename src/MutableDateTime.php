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

/**
 * A mutable datetime instance that implements the ChronosInterface.
 *
 * This object can be mutated in place using any setter method,
 * or __set().
 *
 * @property-read int $year
 * @property-read int $yearIso
 * @property-read int $month
 * @property-read int $day
 * @property-read int $hour
 * @property-read int $minute
 * @property-read int $second
 * @property-read int $timestamp seconds since the Unix Epoch
 * @property-read DateTimeZone $timezone the current timezone
 * @property-read DateTimeZone $tz alias of timezone
 * @property-read int $micro
 * @property-read int $dayOfWeek 1 (for Monday) through 7 (for Sunday)
 * @property-read int $dayOfYear 0 through 365
 * @property-read int $weekOfMonth 1 through 5
 * @property-read int $weekOfYear ISO-8601 week number of year, weeks starting on Monday
 * @property-read int $daysInMonth number of days in the given month
 * @property-read int $age does a diffInYears() with default parameters
 * @property-read int $quarter the quarter of this instance, 1 - 4
 * @property-read int $offset the timezone offset in seconds from UTC
 * @property-read int $offsetHours the timezone offset in hours from UTC
 * @property-read bool $dst daylight savings time indicator, true if DST, false otherwise
 * @property-read bool $local checks if the timezone is local, true if local, false otherwise
 * @property-read bool $utc checks if the timezone is UTC, true if UTC, false otherwise
 * @property-read string  $timezoneName
 * @property-read string  $tzName
 */
class MutableDateTime extends DateTime implements ChronosInterface
{
    use Traits\ComparisonTrait;
    use Traits\DifferenceTrait;
    use Traits\FactoryTrait;
    use Traits\FormattingTrait;
    use Traits\MagicPropertyTrait;
    use Traits\ModifierTrait;
    use Traits\RelativeKeywordTrait;
    use Traits\TestingAidTrait;
    use Traits\TimezoneTrait;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = ChronosInterface::DEFAULT_TO_STRING_FORMAT;

    /**
     * Create a new MutableDateTime instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = 'now', $tz = null)
    {
        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        if (static::$testNow === null) {
            parent::__construct($time === null ? 'now' : $time, $tz);

            return;
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            parent::__construct($time, $tz);

            return;
        }

        $testInstance = clone static::getTestNow();
        if ($relative) {
            $testInstance = $testInstance->modify($time);
        }

        if ($tz !== $testInstance->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz === null ? date_default_timezone_get() : $tz);
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
     * @param string|int|\DateTimeZone $value The value to set.
     * @throws \InvalidArgumentException
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

    /**
     * Overloading original modify method to handling modification with DST change
     *
     * For example, i have the date 2014-03-30 00:00:00 in Europe/London (new Carbon('2014-03-30 00:00:00,
     *   'Europe/London')), then if i want to increase date by 1 day, i expect 2014-03-31 00:00:00, but if want to
     *   increase date by 24 hours, then i expect 2014-03-31 01:00:00, because in this timezone there will be that time
     *   after 24 hours (timezone offset changes because of Daylight correction). The same for minutes and seconds.
     *
     * @param string $modify argument for php DateTime::modify method
     *
     * @return static
     */
    public function modify($modify)
    {
        if (!preg_match('/(sec|second|min|minute|hour)s?/i', $modify)) {
            return parent::modify($modify);
        }

        $timezone = $this->getTimezone();
        $this->setTimezone('UTC');
        parent::modify($modify);
        $this->setTimezone($timezone);

        return $this;
    }

    /**
     * Return properties for debugging.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $properties = [
            'time' => $this->format('Y-m-d H:i:s.u'),
            'timezone' => $this->getTimezone()->getName(),
            'hasFixedNow' => isset(self::$testNow)
        ];

        return $properties;
    }
}
