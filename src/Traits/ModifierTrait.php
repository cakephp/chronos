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
namespace Cake\Chronos\Traits;

use Cake\Chronos\ChronosInterface;
use DateTime;
use DateTimeImmutable;

/**
 * Provides a suite of modifier methods.
 *
 * These methods let you modify the various aspects
 * of a DateTime with a series of fluent methods.
 *
 * This trait expects that the implementing class
 * also implements a copy() method. This method can be obtained
 * using the CopyTrait.
 */
trait ModifierTrait
{

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static $days = [
        ChronosInterface::MONDAY => 'Monday',
        ChronosInterface::TUESDAY => 'Tuesday',
        ChronosInterface::WEDNESDAY => 'Wednesday',
        ChronosInterface::THURSDAY => 'Thursday',
        ChronosInterface::FRIDAY => 'Friday',
        ChronosInterface::SATURDAY => 'Saturday',
        ChronosInterface::SUNDAY => 'Sunday',
    ];

    /**
     * First day of week
     *
     * @var int
     */
    protected static $weekStartsAt = ChronosInterface::MONDAY;

    /**
     * Last day of week
     *
     * @var int
     */
    protected static $weekEndsAt = ChronosInterface::SUNDAY;

    /**
     * Get the first day of week
     *
     * @return int
     */
    public static function getWeekStartsAt()
    {
        return static::$weekStartsAt;
    }

    /**
     * Set the first day of week
     *
     * @param int $day The day the week starts with.
     * @return void
     */
    public static function setWeekStartsAt($day)
    {
        static::$weekStartsAt = $day;
    }

    /**
     * Get the last day of week
     *
     * @return int
     */
    public static function getWeekEndsAt()
    {
        return static::$weekEndsAt;
    }

    /**
     * Set the last day of week
     *
     * @param int $day The day the week ends with.
     * @return void
     */
    public static function setWeekEndsAt($day)
    {
        static::$weekEndsAt = $day;
    }

    /**
     * Set the date to a different date.
     *
     * Workaround for a PHP bug related to the first day of a month
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @return static
     * @see https://bugs.php.net/bug.php?id=63863
     */
    public function setDate($year, $month, $day)
    {
        // Workaround for PHP issue.
        $date = $this->modify('+0 day');

        if ($this instanceof DateTimeImmutable) {
            // Reflection is necessary to access the parent method
            // of the immutable object
            $method = new \ReflectionMethod('DateTimeImmutable', 'setDate');

            return $method->invoke($date, $year, $month, $day);
        }

        return parent::setDate($year, $month, $day);
    }

    /**
     * Set the date and time all together
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @param int $hour The hour to set.
     * @param int $minute The minute to set.
     * @param int $second The second to set.
     * @return static
     */
    public function setDateTime($year, $month, $day, $hour, $minute, $second = 0)
    {
        return $this->setDate($year, $month, $day)->setTime($hour, $minute, $second);
    }

    /**
     * Set the time by time string
     *
     * @param string $time Time as string.
     * @return static
     */
    public function setTimeFromTimeString($time)
    {
        $time = explode(":", $time);
        $hour = $time[0];
        $minute = isset($time[1]) ? $time[1] : 0;
        $second = isset($time[2]) ? $time[2] : 0;

        return $this->setTime($hour, $minute, $second);
    }

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function timestamp($value)
    {
        return $this->setTimestamp($value);
    }

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return static
     */
    public function year($value)
    {
        return $this->setDate($value, $this->month, $this->day);
    }

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return static
     */
    public function month($value)
    {
        return $this->setDate($this->year, $value, $this->day);
    }

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return static
     */
    public function day($value)
    {
        return $this->setDate($this->year, $this->month, $value);
    }

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return static
     */
    public function hour($value)
    {
        return $this->setTime($value, $this->minute, $this->second);
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute($value)
    {
        return $this->setTime($this->hour, $value, $this->second);
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second($value)
    {
        return $this->setTime($this->hour, $this->minute, $value);
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYears($value)
    {
        return $this->modify((int)$value . ' year');
    }

    /**
     * Add a year to the instance
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYear($value = 1)
    {
        return $this->addYears($value);
    }

    /**
     * Remove a year from the instance
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYear($value = 1)
    {
        return $this->subYears($value);
    }

    /**
     * Remove years from the instance.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYears($value)
    {
        return $this->addYears(-1 * $value);
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-01-03'))->addMonths(1); // Results in 2015-02-03
     *
     *  (new Chronos('2015-01-31'))->addMonths(1); // Results in 2015-02-28
     * ```
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonths($value)
    {
        $day = $this->day;
        $date = $this->modify((int)$value . ' month');

        if ($date->day !== $day) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Add a month to the instance
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-01-03'))->addMonth(); // Results in 2015-02-03
     *
     *  (new Chronos('2015-01-31'))->addMonth(); // Results in 2015-02-28
     * ```
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonth($value = 1)
    {
        return $this->addMonths($value);
    }

    /**
     * Remove a month from the instance
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-03-01'))->subMonth(); // Results in 2015-02-01
     *
     *  (new Chronos('2015-03-31'))->subMonth(); // Results in 2015-02-28
     * ```
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonth($value = 1)
    {
        return $this->subMonths($value);
    }

    /**
     * Remove months from the instance
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-03-01'))->subMonths(1); // Results in 2015-02-01
     *
     *  (new Chronos('2015-03-31'))->subMonths(1); // Results in 2015-02-28
     * ```
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonths($value)
    {
        return $this->addMonths(-1 * $value);
    }

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthsWithOverflow($value)
    {
        return $this->modify((int)$value . ' month');
    }

    /**
     * Add a month with overflow to the instance
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthWithOverflow($value = 1)
    {
        return $this->modify((int)$value . ' month');
    }

    /**
     * Remove a month with overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthWithOverflow($value = 1)
    {
        return $this->subMonthsWithOverflow($value);
    }

    /**
     * Remove months with overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthsWithOverflow($value)
    {
        return $this->addMonthsWithOverflow(-1 * $value);
    }

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDays($value)
    {
        $value = (int)$value;

        return $this->modify("$value day");
    }

    /**
     * Add a day to the instance
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDay($value = 1)
    {
        $value = (int)$value;

        return $this->modify("$value day");
    }

    /**
     * Remove a day from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDay($value = 1)
    {
        $value = (int)$value;

        return $this->modify("-$value day");
    }

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDays($value)
    {
        $value = (int)$value;

        return $this->modify("-$value day");
    }

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekdays($value)
    {
        return $this->modify((int)$value . ' weekdays ' . $this->format('H:i:s'));
    }

    /**
     * Add a weekday to the instance
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekday($value = 1)
    {
        return $this->addWeekdays($value);
    }

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekdays($value)
    {
        $value = (int)$value;

        return $this->modify("$value weekdays ago, " . $this->format('H:i:s'));
    }

    /**
     * Remove a weekday from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekday($value = 1)
    {
        return $this->subWeekdays($value);
    }

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeeks($value)
    {
        $value = (int)$value;

        return $this->modify("$value week");
    }

    /**
     * Add a week to the instance
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeek($value = 1)
    {
        $value = (int)$value;

        return $this->modify("$value week");
    }

    /**
     * Remove a week from the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeek($value = 1)
    {
        $value = (int)$value;

        return $this->modify("-$value week");
    }

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeeks($value)
    {
        $value = (int)$value;

        return $this->modify("-$value week");
    }

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHours($value)
    {
        $value = (int)$value;

        return $this->modify("$value hour");
    }

    /**
     * Add an hour to the instance
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHour($value = 1)
    {
        $value = (int)$value;

        return $this->modify("$value hour");
    }

    /**
     * Remove an hour from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHour($value = 1)
    {
        $value = (int)$value;

        return $this->modify("-$value hour");
    }

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHours($value)
    {
        $value = (int)$value;

        return $this->modify("-$value hour");
    }

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinutes($value)
    {
        $value = (int)$value;

        return $this->modify("$value minute");
    }

    /**
     * Add a minute to the instance
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinute($value = 1)
    {
        $value = (int)$value;

        return $this->modify("$value minute");
    }

    /**
     * Remove a minute from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinute($value = 1)
    {
        $value = (int)$value;

        return $this->modify("-$value minute");
    }

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinutes($value)
    {
        $value = (int)$value;

        return $this->modify("-$value minute");
    }

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSeconds($value)
    {
        $value = (int)$value;

        return $this->modify("$value second");
    }

    /**
     * Add a second to the instance
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSecond($value = 1)
    {
        $value = (int)$value;

        return $this->modify("$value second");
    }

    /**
     * Remove a second from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSecond($value = 1)
    {
        $value = (int)$value;

        return $this->modify("-$value second");
    }

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSeconds($value)
    {
        $value = (int)$value;

        return $this->modify("-$value second");
    }

    /**
     * Resets the time to 00:00:00
     *
     * @return static
     */
    public function startOfDay()
    {
        return $this->modify('midnight');
    }

    /**
     * Resets the time to 23:59:59
     *
     * @return static
     */
    public function endOfDay()
    {
        return $this->modify('23:59:59');
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return static
     */
    public function startOfMonth()
    {
        return $this->modify('first day of this month midnight');
    }

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return static
     */
    public function endOfMonth()
    {
        return $this->modify('last day of this month, 23:59:59');
    }

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return static
     */
    public function startOfYear()
    {
        return $this->modify('first day of january midnight');
    }

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return static
     */
    public function endOfYear()
    {
        return $this->modify('last day of december, 23:59:59');
    }

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return static
     */
    public function startOfDecade()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return static
     */
    public function endOfDecade()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE + ChronosInterface::YEARS_PER_DECADE - 1;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return static
     */
    public function startOfCentury()
    {
        $year = $this->startOfYear()->year(($this->year - 1) - ($this->year - 1) % ChronosInterface::YEARS_PER_CENTURY + 1)->year;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return static
     */
    public function endOfCentury()
    {
        $year = $this->endOfYear()->year(($this->year - 1) - ($this->year - 1) % ChronosInterface::YEARS_PER_CENTURY + ChronosInterface::YEARS_PER_CENTURY)->year;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return static
     */
    public function startOfWeek()
    {
        $dt = $this;
        if ($dt->dayOfWeek !== static::$weekStartsAt) {
            $dt = $dt->previous(static::$weekStartsAt);
        }

        return $dt->startOfDay();
    }

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59
     *
     * @return static
     */
    public function endOfWeek()
    {
        $dt = $this;
        if ($dt->dayOfWeek !== static::$weekEndsAt) {
            $dt = $dt->next(static::$weekEndsAt);
        }

        return $dt->endOfDay();
    }

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function next($dayOfWeek = null)
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek;
        }

        $day = static::$days[$dayOfWeek];

        return $this->modify("next $day, midnight");
    }

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function previous($dayOfWeek = null)
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek;
        }

        $day = static::$days[$dayOfWeek];

        return $this->modify("last $day, midnight");
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfMonth($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("first $day of this month, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfMonth($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("last $day of this month, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfMonth($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfMonth();
        $check = $dt->format('Y-m');
        $dt = $dt->modify("+$nth " . static::$days[$dayOfWeek]);

        return ($dt->format('Y-m') === $check) ? $dt : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER - 2)->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER)->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfQuarter($nth, $dayOfWeek)
    {
        $dt = $this->copy()->day(1)->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER);
        $lastMonth = $dt->month;
        $year = $dt->year;
        $dt = $dt->firstOfQuarter()->modify("+$nth" . static::$days[$dayOfWeek]);

        return ($lastMonth < $dt->month || $year !== $dt->year) ? false : $dt;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfYear($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("first $day of january, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfYear($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("last $day of december, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfYear($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfYear()->modify("+$nth " . static::$days[$dayOfWeek]);

        return $this->year === $dt->year ? $dt : false;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return static
     */
    public function average(ChronosInterface $dt = null)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;

        return $this->addSeconds((int)($this->diffInSeconds($dt, false) / 2));
    }
}
