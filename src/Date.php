<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

use DateTimeImmutable;
use DateTimeZone;

/**
 * An immutable date object that converts all time components into 00:00:00.
 *
 * This class is useful when you want to represent a calendar date and ignore times.
 * This means that timezone changes take no effect as a calendar date exists in all timezones
 * in each respective date.
 */
class Date extends DateTimeImmutable implements ChronosInterface
{
    use Traits\ComparisonTrait;
    use Traits\DifferenceTrait;
    use Traits\FactoryTrait;
    use Traits\FormattingTrait;
    use Traits\MagicPropertyTrait;
    use Traits\ModifierTrait;
    use Traits\RelativeKeywordTrait;
    use Traits\TestingAidTrait;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = 'Y-m-d';

    /**
     * Create a new Immutable Date instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * Date instances lack time components, however due to limitations in PHP's
     * internal Datetime object the time will always be set to 00:00:00, and the
     * timezone will always be UTC. Normalizing the timezone allows for
     * subtraction/addition to have deterministic results.
     *
     * @param string|null $time Fixed or relative time
     * @param DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = 'now', $tz = null)
    {
        $tz = new DateTimeZone('UTC');
        if (static::$testNow === null) {
            $time = $this->stripTime($time);
            return parent::__construct($time, $tz);
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            $time = $this->stripTime($time);
            return parent::__construct($time, $tz);
        }

        $testInstance = static::getTestNow();
        if ($relative) {
            $testInstance = $testInstance->modify($time);
        }

        if ($tz !== $testInstance->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz === null ? date_default_timezone_get() : $tz);
        }

        $time = $testInstance->format('Y-m-d 00:00:00');
        parent::__construct($time, $tz);
    }

    /**
     * Removes the time components from an input string.
     *
     * Used to ensure constructed objects always lack time.
     *
     * @param string|int $time The input time. Integer values will be assumed
     *   to be in UTC. The 'now' and '' values will use the current local time.
     * @return string The date component of $time.
     */
    protected function stripTime($time)
    {
        if (substr($time, 0, 1) === '@') {
            return gmdate('Y-m-d 00:00:00', substr($time, 1));
        }
        if (is_int($time) || ctype_digit($time)) {
            return gmdate('Y-m-d 00:00:00', $time);
        }
        if ($time === null || $time === 'now' || $time === '') {
            return date('Y-m-d 00:00:00');
        }
        return preg_replace('/\d{1,2}:\d{1,2}:\d{1,2}/', '00:00:00', $time);
    }

    /**
     * Modify the time on the Date.
     *
     * This method ignores all inputs and forces all inputs to 0.
     *
     * @param int $hours The hours to set (ignored)
     * @param int $minutes The hours to set (ignored)
     * @param int $seconds The hours to set (ignored)
     * @return static A modified Date instance.
     */
    public function setTime($hours, $minutes, $seconds = 0)
    {
        return parent::setTime(0, 0, 0);
    }

    /**
     * Add an Interval to a Date
     *
     * Any changes to the time will be ignored and reset to 00:00:00
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    public function add($interval)
    {
        $date = parent::add($interval);
        if ($date->format('H:i:s') !== '00:00:00') {
            return $date->setTime(0, 0, 0);
        }
        return $date;
    }

    /**
     * Subtract an Interval from a Date.
     *
     * Any changes to the time will be ignored and reset to 00:00:00
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    public function sub($interval)
    {
        $date = parent::sub($interval);
        if ($date->format('H:i:s') !== '00:00:00') {
            return $date->setTime(0, 0, 0);
        }
        return $date;
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function timezone($value)
    {
        return $this;
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function tz($value)
    {
        return $this;
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function setTimezone($value)
    {
        return $this;
    }

    /**
     * Set the timestamp value and get a new object back.
     *
     * This method will discard the time aspects of the timestamp
     * and only apply the date portions
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function setTimestamp($value)
    {
        $date = date('Y-m-d 00:00:00', $value);
        return parent::setTimestamp(strtotime($date));
    }

    /**
     * Overloaded to ignore time changes.
     *
     * Changing any aspect of the time will be ignored, and the resulting object
     * will have its time frozen to 00:00:00.
     *
     * @param string $relative The relative change to make.
     * @return static A new date with the applied date changes.
     */
    public function modify($relative)
    {
        if (preg_match('/hour|minute|second/', $relative)) {
            return $this;
        }
        $new = parent::modify($relative);
        if ($new->format('H:i:s') !== '00:00:00') {
            return $new->setTime(0, 0, 0);
        }
        return $new;
    }

    /**
     * Create a new mutable instance from current immutable instance.
     *
     * @return \Cake\Chronos\MutableDate
     */
    public function toMutable()
    {
        return MutableDate::instance($this);
    }
}
