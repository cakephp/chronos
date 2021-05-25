<?php
declare(strict_types=1);

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
    public static function getWeekStartsAt(): int
    {
        return static::$weekStartsAt;
    }

    /**
     * Set the first day of week
     *
     * @param int $day The day the week starts with.
     * @return void
     */
    public static function setWeekStartsAt(int $day): void
    {
        static::$weekStartsAt = $day;
    }

    /**
     * Get the last day of week
     *
     * @return int
     */
    public static function getWeekEndsAt(): int
    {
        return static::$weekEndsAt;
    }

    /**
     * Set the last day of week
     *
     * @param int $day The day the week ends with.
     * @return void
     */
    public static function setWeekEndsAt(int $day): void
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
     * @return self
     */
    public function setDate($year, $month, $day): self
    {
        return $this->modify('+0 day')->setDateParent($year, $month, $day);
    }

    /**
     * Just calling to parent setDate
     * It used in overwritten setDate
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @return self
     */
    private function setDateParent(int $year, int $month, int $day): self
    {
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
     * @return self
     */
    public function setDateTime(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second = 0
    ): self {
        return $this->setDate($year, $month, $day)->setTime($hour, $minute, $second);
    }

    /**
     * Set the time by time string
     *
     * @param string $time Time as string.
     * @return self
     */
    public function setTimeFromTimeString(string $time): self
    {
        $time = explode(':', $time);
        $hour = $time[0];
        $minute = $time[1] ?? 0;
        $second = $time[2] ?? 0;

        return $this->setTime((int)$hour, (int)$minute, (int)$second);
    }

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return self
     */
    public function timestamp(int $value): self
    {
        return $this->setTimestamp($value);
    }

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return self
     */
    public function year(int $value): self
    {
        return $this->setDate($value, $this->month, $this->day);
    }

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return self
     */
    public function month(int $value): self
    {
        return $this->setDate($this->year, $value, $this->day);
    }

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return self
     */
    public function day(int $value): self
    {
        return $this->setDate($this->year, $this->month, $value);
    }

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return self
     */
    public function hour(int $value): self
    {
        return $this->setTime($value, $this->minute, $this->second);
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return self
     */
    public function minute(int $value): self
    {
        return $this->setTime($this->hour, $value, $this->second);
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return self
     */
    public function second(int $value): self
    {
        return $this->setTime($this->hour, $this->minute, $value);
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * If the new date does not exist, the last day of the month is used
     * instead instead of overflowing into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-01-03'))->addYears(1); // Results in 2016-01-03
     *
     *  (new Chronos('2012-02-29'))->addYears(1); // Results in 2013-02-28
     * ```
     *
     * @param int $value The number of years to add.
     * @return self
     */
    public function addYears(int $value): self
    {
        $month = $this->month;
        $date = $this->modify($value . ' year');

        if ($date->month !== $month) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Add a year to the instance.
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to add.
     * @return self
     */
    public function addYear(int $value = 1): self
    {
        return $this->addYears($value);
    }

    /**
     * Remove years from the instance.
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to remove.
     * @return self
     */
    public function subYears(int $value): self
    {
        return $this->addYears(-1 * $value);
    }

    /**
     * Remove a year from the instance
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to remove.
     * @return self
     */
    public function subYear(int $value = 1): self
    {
        return $this->subYears($value);
    }

    /**
     * Add years with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * If the new date does not exist, the days overflow into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2012-02-29'))->addYearsWithOverflow(1); // Results in 2013-03-01
     * ```
     *
     * @param int $value The number of years to add.
     * @return self
     */
    public function addYearsWithOverflow(int $value): self
    {
        return $this->modify($value . ' year');
    }

    /**
     * Add a year with overflow to the instance.
     *
     * Has the same behavior as `addYearsWithOverflow()`.
     *
     * @param int $value The number of years to add.
     * @return self
     */
    public function addYearWithOverflow(int $value = 1): self
    {
        return $this->addYearsWithOverflow($value);
    }

    /**
     * Remove years with overflow from the instance
     *
     * Has the same behavior as `addYeasrWithOverflow()`.
     *
     * @param int $value The number of years to remove.
     * @return self
     */
    public function subYearsWithOverflow(int $value): self
    {
        return $this->addYearsWithOverflow(-1 * $value);
    }

    /**
     * Remove a year with overflow from the instance.
     *
     * Has the same behavior as `addYearsWithOverflow()`.
     *
     * @param int $value The number of years to remove.
     * @return self
     */
    public function subYearWithOverflow(int $value = 1): self
    {
        return $this->subYearsWithOverflow($value);
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
     * @return self
     */
    public function addMonths(int $value): self
    {
        $day = $this->day;
        $date = $this->modify($value . ' month');

        if ($date->day !== $day) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Add a month to the instance
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to add.
     * @return self
     */
    public function addMonth(int $value = 1): self
    {
        return $this->addMonths($value);
    }

    /**
     * Remove a month from the instance
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to remove.
     * @return self
     */
    public function subMonth(int $value = 1): self
    {
        return $this->subMonths($value);
    }

    /**
     * Remove months from the instance
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to remove.
     * @return self
     */
    public function subMonths(int $value): self
    {
        return $this->addMonths(-1 * $value);
    }

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * If the new date does not exist, the days overflow into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2012-01-30'))->addMonthsWithOverflow(1); // Results in 2013-03-01
     * ```
     *
     * @param int $value The number of months to add.
     * @return self
     */
    public function addMonthsWithOverflow(int $value): self
    {
        return $this->modify($value . ' month');
    }

    /**
     * Add a month with overflow to the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to add.
     * @return self
     */
    public function addMonthWithOverflow(int $value = 1): self
    {
        return $this->modify($value . ' month');
    }

    /**
     * Remove months with overflow from the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to remove.
     * @return self
     */
    public function subMonthsWithOverflow(int $value): self
    {
        return $this->addMonthsWithOverflow(-1 * $value);
    }

    /**
     * Remove a month with overflow from the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to remove.
     * @return self
     */
    public function subMonthWithOverflow(int $value = 1): self
    {
        return $this->subMonthsWithOverflow($value);
    }

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return self
     */
    public function addDays(int $value): self
    {
        return $this->modify("$value day");
    }

    /**
     * Add a day to the instance
     *
     * @param int $value The number of days to add.
     * @return self
     */
    public function addDay(int $value = 1): self
    {
        return $this->modify("$value day");
    }

    /**
     * Remove a day from the instance
     *
     * @param int $value The number of days to remove.
     * @return self
     */
    public function subDay(int $value = 1): self
    {
        return $this->modify("-$value day");
    }

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return self
     */
    public function subDays(int $value): self
    {
        return $this->modify("-$value day");
    }

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return self
     */
    public function addWeekdays(int $value): self
    {
        return $this->modify((int)$value . ' weekdays ' . $this->format('H:i:s'));
    }

    /**
     * Add a weekday to the instance
     *
     * @param int $value The number of weekdays to add.
     * @return self
     */
    public function addWeekday(int $value = 1): self
    {
        return $this->addWeekdays($value);
    }

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return self
     */
    public function subWeekdays(int $value): self
    {
        return $this->modify("$value weekdays ago, " . $this->format('H:i:s'));
    }

    /**
     * Remove a weekday from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return self
     */
    public function subWeekday(int $value = 1): self
    {
        return $this->subWeekdays($value);
    }

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return self
     */
    public function addWeeks(int $value): self
    {
        return $this->modify("$value week");
    }

    /**
     * Add a week to the instance
     *
     * @param int $value The number of weeks to add.
     * @return self
     */
    public function addWeek(int $value = 1): self
    {
        return $this->modify("$value week");
    }

    /**
     * Remove a week from the instance
     *
     * @param int $value The number of weeks to remove.
     * @return self
     */
    public function subWeek(int $value = 1): self
    {
        return $this->modify("-$value week");
    }

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return self
     */
    public function subWeeks(int $value): self
    {
        return $this->modify("-$value week");
    }

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return self
     */
    public function addHours(int $value): self
    {
        return $this->modify("$value hour");
    }

    /**
     * Add an hour to the instance
     *
     * @param int $value The number of hours to add.
     * @return self
     */
    public function addHour(int $value = 1): self
    {
        return $this->modify("$value hour");
    }

    /**
     * Remove an hour from the instance
     *
     * @param int $value The number of hours to remove.
     * @return self
     */
    public function subHour(int $value = 1): self
    {
        return $this->modify("-$value hour");
    }

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return self
     */
    public function subHours(int $value): self
    {
        return $this->modify("-$value hour");
    }

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return self
     */
    public function addMinutes(int $value): self
    {
        return $this->modify("$value minute");
    }

    /**
     * Add a minute to the instance
     *
     * @param int $value The number of minutes to add.
     * @return self
     */
    public function addMinute(int $value = 1): self
    {
        return $this->modify("$value minute");
    }

    /**
     * Remove a minute from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return self
     */
    public function subMinute(int $value = 1): self
    {
        return $this->modify("-$value minute");
    }

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return self
     */
    public function subMinutes(int $value): self
    {
        return $this->modify("-$value minute");
    }

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return self
     */
    public function addSeconds(int $value): self
    {
        return $this->modify("$value second");
    }

    /**
     * Add a second to the instance
     *
     * @param int $value The number of seconds to add.
     * @return self
     */
    public function addSecond(int $value = 1): self
    {
        return $this->modify("$value second");
    }

    /**
     * Remove a second from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return self
     */
    public function subSecond(int $value = 1): self
    {
        return $this->modify("-$value second");
    }

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return self
     */
    public function subSeconds(int $value): self
    {
        return $this->modify("-$value second");
    }

    /**
     * Resets the time to 00:00:00
     *
     * @return self
     */
    public function startOfDay(): self
    {
        return $this->modify('midnight');
    }

    /**
     * Resets the time to 23:59:59
     *
     * @return self
     */
    public function endOfDay(): self
    {
        return $this->modify('23:59:59');
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return self
     */
    public function startOfMonth(): self
    {
        return $this->modify('first day of this month midnight');
    }

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return self
     */
    public function endOfMonth(): self
    {
        return $this->modify('last day of this month, 23:59:59');
    }

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return self
     */
    public function startOfYear(): self
    {
        return $this->modify('first day of january midnight');
    }

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return self
     */
    public function endOfYear(): self
    {
        return $this->modify('last day of december, 23:59:59');
    }

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return self
     */
    public function startOfDecade(): self
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return self
     */
    public function endOfDecade(): self
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE + ChronosInterface::YEARS_PER_DECADE - 1;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return self
     */
    public function startOfCentury(): self
    {
        $year = $this->startOfYear()
            ->year($this->year - 1 - ($this->year - 1) % ChronosInterface::YEARS_PER_CENTURY + 1)
            ->year;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return self
     */
    public function endOfCentury(): self
    {
        $y = $this->year - 1
            - ($this->year - 1)
            % ChronosInterface::YEARS_PER_CENTURY
            + ChronosInterface::YEARS_PER_CENTURY;

        $year = $this->endOfYear()
            ->year($y)
            ->year;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return self
     */
    public function startOfWeek(): self
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
     * @return self
     */
    public function endOfWeek(): self
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
     * @return self
     */
    public function next(?int $dayOfWeek = null): self
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
     * @return self
     */
    public function previous(?int $dayOfWeek = null): self
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
     * @return self
     */
    public function firstOfMonth(?int $dayOfWeek = null): self
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
     * @return self
     */
    public function lastOfMonth(?int $dayOfWeek = null): self
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
     * @return self|false
     */
    public function nthOfMonth(int $nth, int $dayOfWeek)
    {
        $dt = $this->copy()->firstOfMonth();
        $check = $dt->format('Y-m');
        $dt = $dt->modify("+$nth " . static::$days[$dayOfWeek]);

        return $dt->format('Y-m') === $check ? $dt : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return self
     */
    public function firstOfQuarter(?int $dayOfWeek = null): self
    {
        return $this
            ->day(1)
            ->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER - 2)
            ->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return self
     */
    public function lastOfQuarter(?int $dayOfWeek = null): self
    {
        return $this
            ->day(1)
            ->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER)
            ->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return self|false
     */
    public function nthOfQuarter(int $nth, int $dayOfWeek)
    {
        $dt = $this->copy()->day(1)->month($this->quarter * ChronosInterface::MONTHS_PER_QUARTER);
        $lastMonth = $dt->month;
        $year = $dt->year;
        $dt = $dt->firstOfQuarter()->modify("+$nth" . static::$days[$dayOfWeek]);

        return $lastMonth < $dt->month || $year !== $dt->year ? false : $dt;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return self
     */
    public function firstOfYear(?int $dayOfWeek = null): self
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
     * @return self
     */
    public function lastOfYear(?int $dayOfWeek = null): self
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
     * @return self|false
     */
    public function nthOfYear(int $nth, int $dayOfWeek)
    {
        $dt = $this->copy()->firstOfYear()->modify("+$nth " . static::$days[$dayOfWeek]);

        return $this->year === $dt->year ? $dt : false;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return self
     */
    public function average(?ChronosInterface $dt = null): self
    {
        $dt = $dt ?? static::now($this->tz);

        return $this->addSeconds((int)($this->diffInSeconds($dt, false) / 2));
    }
}
