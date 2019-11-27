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

use DateTimeInterface;
use DateTimeZone;

/**
 * An extension to the DateTimeInterface for a friendlier API
 *
 * @method static modify(string $relative)
 */
interface ChronosInterface extends DateTimeInterface
{
    /**
     * The day constants
     */
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * Number of X in Y
     */
    const YEARS_PER_CENTURY = 100;
    const YEARS_PER_DECADE = 10;
    const MONTHS_PER_YEAR = 12;
    const MONTHS_PER_QUARTER = 3;
    const WEEKS_PER_YEAR = 52;
    const DAYS_PER_WEEK = 7;
    const HOURS_PER_DAY = 24;
    const MINUTES_PER_HOUR = 60;
    const SECONDS_PER_MINUTE = 60;

    /**
     * Default format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    const DEFAULT_TO_STRING_FORMAT = 'Y-m-d H:i:s';

    /**
     * Get a ChronosInterface instance for the current date and time
     *
     * @param \DateTimeZone|string|null $tz The DateTimeZone object or timezone name.
     * @return static
     */
    public static function now($tz);

    /**
     * Get a copy of the instance
     *
     * @return static
     */
    public function copy();

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return static
     */
    public function year($value);

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return static
     */
    public function month($value);

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return static
     */
    public function day($value);

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return static
     */
    public function hour($value);

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute($value);

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second($value);

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
    public function setDateTime($year, $month, $day, $hour, $minute, $second = 0);

    /**
     * Set the time by time string
     *
     * @param string $time Time as string.
     * @return static
     */
    public function setTimeFromTimeString($time);

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function timestamp($value);

    /**
     * Alias for setTimezone()
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function timezone($value);

    /**
     * Alias for setTimezone()
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function tz($value);

    /**
     * Set the instance's timezone from a string or object
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function setTimezone($value);

    /**
     * Format the instance as date
     *
     * @return string
     */
    public function toDateString();

    /**
     * Format the instance as a readable date
     *
     * @return string
     */
    public function toFormattedDateString();

    /**
     * Format the instance as time
     *
     * @return string
     */
    public function toTimeString();

    /**
     * Format the instance as date and time
     *
     * @return string
     */
    public function toDateTimeString();

    /**
     * Format the instance with day, date and time
     *
     * @return string
     */
    public function toDayDateTimeString();

    /**
     * Format the instance as ATOM
     *
     * @return string
     */
    public function toAtomString();

    /**
     * Format the instance as COOKIE
     *
     * @return string
     */
    public function toCookieString();

    /**
     * Format the instance as ISO8601
     *
     * @return string
     */
    public function toIso8601String();

    /**
     * Format the instance as RFC822
     *
     * @return string
     */
    public function toRfc822String();

    /**
     * Format the instance as RFC850
     *
     * @return string
     */
    public function toRfc850String();

    /**
     * Format the instance as RFC1036
     *
     * @return string
     */
    public function toRfc1036String();

    /**
     * Format the instance as RFC1123
     *
     * @return string
     */
    public function toRfc1123String();

    /**
     * Format the instance as RFC2822
     *
     * @return string
     */
    public function toRfc2822String();

    /**
     * Format the instance as RFC3339
     *
     * @return string
     */
    public function toRfc3339String();

    /**
     * Format the instance as RSS
     *
     * @return string
     */
    public function toRssString();

    /**
     * Format the instance as W3C
     *
     * @return string
     */
    public function toW3cString();

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see equals
     */
    public function eq(ChronosInterface $dt);

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function equals(ChronosInterface $dt);

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see notEquals
     */
    public function ne(ChronosInterface $dt);

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function notEquals(ChronosInterface $dt);

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see greaterThan
     */
    public function gt(ChronosInterface $dt);

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function greaterThan(ChronosInterface $dt);

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see greaterThanOrEquals
     */
    public function gte(ChronosInterface $dt);

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function greaterThanOrEquals(ChronosInterface $dt);

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see lessThan
     */
    public function lt(ChronosInterface $dt);

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lessThan(ChronosInterface $dt);

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see lessThanOrEquals
     */
    public function lte(ChronosInterface $dt);

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lessThanOrEquals(ChronosInterface $dt);

    /**
     * Determines if the instance is between two others
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @param bool $equal Indicates if a > and < comparison should be used or <= or >=
     * @return bool
     */
    public function between(ChronosInterface $dt1, ChronosInterface $dt2, $equal = true);

    /**
     * Get the closest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return static
     */
    public function closest(ChronosInterface $dt1, ChronosInterface $dt2);

    /**
     * Get the farthest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return static
     */
    public function farthest(ChronosInterface $dt1, ChronosInterface $dt2);

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return static
     */
    public function min(ChronosInterface $dt = null);

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return static
     */
    public function max(ChronosInterface $dt = null);

    /**
     * Determines if the instance is a weekday
     *
     * @return bool
     */
    public function isWeekday();

    /**
     * Determines if the instance is a weekend day
     *
     * @return bool
     */
    public function isWeekend();

    /**
     * Determines if the instance is yesterday
     *
     * @return bool
     */
    public function isYesterday();

    /**
     * Determines if the instance is today
     *
     * @return bool
     */
    public function isToday();

    /**
     * Determines if the instance is tomorrow
     *
     * @return bool
     */
    public function isTomorrow();

    /**
     * Determines if the instance is in the future, ie. greater (after) than now
     *
     * @return bool
     */
    public function isFuture();

    /**
     * Determines if the instance is in the past, ie. less (before) than now
     *
     * @return bool
     */
    public function isPast();

    /**
     * Determines if the instance is a leap year
     *
     * @return bool
     */
    public function isLeapYear();

    /**
     * Checks if the passed in date is the same day as the instance current day.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to check against.
     * @return bool
     */
    public function isSameDay(ChronosInterface $dt);

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday();

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday();

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday();

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday();

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday();

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday();

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday();

    /**
     * Returns true if this object represents a date within the current week
     *
     * @return bool
     */
    public function isThisWeek();

    /**
     * Returns true if this object represents a date within the current month
     *
     * @return bool
     */
    public function isThisMonth();

    /**
     * Returns true if this object represents a date within the current year
     *
     * @return bool
     */
    public function isThisYear();

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYears($value);

    /**
     * Add a year to the instance
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYear($value = 1);

    /**
     * Remove a year from the instance
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYear($value = 1);

    /**
     * Remove years from the instance.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYears($value);

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
    public function addMonths($value);

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
    public function addMonth($value = 1);

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
    public function subMonth($value = 1);

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
    public function subMonths($value);

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthsWithOverflow($value);

    /**
     * Add a month with overflow to the instance
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthWithOverflow($value = 1);

    /**
     * Remove a month with overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthWithOverflow($value = 1);

    /**
     * Remove months with overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthsWithOverflow($value);

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDays($value);

    /**
     * Add a day to the instance
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDay($value = 1);

    /**
     * Remove a day from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDay($value = 1);

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDays($value);

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekdays($value);

    /**
     * Add a weekday to the instance
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekday($value = 1);

    /**
     * Remove a weekday from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekday($value = 1);

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekdays($value);

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeeks($value);

    /**
     * Add a week to the instance
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeek($value = 1);

    /**
     * Remove a week from the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeek($value = 1);

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeeks($value);

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHours($value);

    /**
     * Add an hour to the instance
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHour($value = 1);

    /**
     * Remove an hour from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHour($value = 1);

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHours($value);

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinutes($value);

    /**
     * Add a minute to the instance
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinute($value = 1);

    /**
     * Remove a minute from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinute($value = 1);

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinutes($value);

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSeconds($value);

    /**
     * Add a second to the instance
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSecond($value = 1);

    /**
     * Remove a second from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSecond($value = 1);

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSeconds($value);

    /**
     * Get the difference in a human readable format in the current locale.
     *
     * When comparing a value in the past to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the future to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the past to another value:
     * 1 hour before
     * 5 months before
     *
     * When comparing a value in the future to another value:
     * 1 hour after
     * 5 months after
     *
     * @param \Cake\Chronos\ChronosInterface|null $other The datetime to compare with.
     * @param bool $absolute Removes time difference modifiers ago, after, etc
     * @return string
     */
    public function diffForHumans(ChronosInterface $other = null, $absolute = false);

    /**
     * Get the difference in years
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInYears(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in months
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in weeks
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in days
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDays(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(callable $callback, ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(callable $callback, ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference by the given interval using a filter callable
     *
     * @param \Cake\Chronos\ChronosInterval $ci An interval to traverse by
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffFiltered(ChronosInterval $ci, callable $callback, ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in weekdays
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in weekend days using a filter
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in hours
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHours(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in minutes
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(ChronosInterface $dt = null, $abs = true);

    /**
     * Get the difference in seconds
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(ChronosInterface $dt = null, $abs = true);

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight();

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay();

    /**
     * Resets the time to 00:00:00
     *
     * @return static
     */
    public function startOfDay();

    /**
     * Resets the time to 23:59:59
     *
     * @return static
     */
    public function endOfDay();

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return static
     */
    public function startOfMonth();

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return static
     */
    public function endOfMonth();

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return static
     */
    public function startOfYear();

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return static
     */
    public function endOfYear();

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return static
     */
    public function startOfDecade();

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return static
     */
    public function endOfDecade();

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return static
     */
    public function startOfCentury();

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return static
     */
    public function endOfCentury();

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return static
     */
    public function startOfWeek();

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59
     *
     * @return static
     */
    public function endOfWeek();

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function next($dayOfWeek = null);

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function previous($dayOfWeek = null);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfMonth($dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfMonth($dayOfWeek = null);

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfMonth($nth, $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfQuarter($dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfQuarter($dayOfWeek = null);

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfQuarter($nth, $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfYear($dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfYear($dayOfWeek = null);

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfYear($nth, $dayOfWeek);

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function average(ChronosInterface $dt = null);

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function isBirthday(ChronosInterface $dt);

    /**
     * Returns true this instance happened within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function wasWithinLast($timeInterval);

    /**
     * Returns true this instance will happen within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function isWithinNext($timeInterval);

    /**
     * Check if instance of ChronosInterface is mutable.
     *
     * @return bool
     */
    public function isMutable();
}
