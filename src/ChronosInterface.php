<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

use DateTimeInterface;

/**
 * An extension to the DateTimeInterface for a friendlier API
 *
 * @method \Cake\Chronos\ChronosInterface modify(string $relative)
 */
interface ChronosInterface extends DateTimeInterface
{
    /**
     * @var int
     */
    public const MONDAY = 1;
    /**
     * @var int
     */
    public const TUESDAY = 2;
    /**
     * @var int
     */
    public const WEDNESDAY = 3;
    /**
     * @var int
     */
    public const THURSDAY = 4;
    /**
     * @var int
     */
    public const FRIDAY = 5;
    /**
     * @var int
     */
    public const SATURDAY = 6;
    /**
     * @var int
     */
    public const SUNDAY = 7;

    /**
     * @var int
     */
    public const YEARS_PER_CENTURY = 100;
    /**
     * @var int
     */
    public const YEARS_PER_DECADE = 10;
    /**
     * @var int
     */
    public const MONTHS_PER_YEAR = 12;
    /**
     * @var int
     */
    public const MONTHS_PER_QUARTER = 3;
    /**
     * @var int
     */
    public const WEEKS_PER_YEAR = 52;
    /**
     * @var int
     */
    public const DAYS_PER_WEEK = 7;
    /**
     * @var int
     */
    public const HOURS_PER_DAY = 24;
    /**
     * @var int
     */
    public const MINUTES_PER_HOUR = 60;
    /**
     * @var int
     */
    public const SECONDS_PER_MINUTE = 60;

    /**
     * Default format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    public const DEFAULT_TO_STRING_FORMAT = 'Y-m-d H:i:s';

    /**
     * Get a ChronosInterface instance for the current date and time
     *
     * @param \DateTimeZone|string|null $tz The DateTimeZone object or timezone name.
     * @return static
     */
    public static function now($tz): self;

    /**
     * Get a copy of the instance
     *
     * @return static
     */
    public function copy(): self;

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return static
     */
    public function year(int $value): self;

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return static
     */
    public function month(int $value): self;

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return static
     */
    public function day(int $value): self;

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return static
     */
    public function hour(int $value): self;

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute(int $value): self;

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second(int $value): self;

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
    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0): self;

    /**
     * Set the time by time string
     *
     * @param string $time Time as string.
     * @return static
     */
    public function setTimeFromTimeString(string $time): self;

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function timestamp(int $value): self;

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
    public function toDateString(): string;

    /**
     * Format the instance as a readable date
     *
     * @return string
     */
    public function toFormattedDateString(): string;

    /**
     * Format the instance as time
     *
     * @return string
     */
    public function toTimeString(): string;

    /**
     * Format the instance as date and time
     *
     * @return string
     */
    public function toDateTimeString(): string;

    /**
     * Format the instance with day, date and time
     *
     * @return string
     */
    public function toDayDateTimeString(): string;

    /**
     * Format the instance as ATOM
     *
     * @return string
     */
    public function toAtomString(): string;

    /**
     * Format the instance as COOKIE
     *
     * @return string
     */
    public function toCookieString(): string;

    /**
     * Format the instance as ISO8601
     *
     * @return string
     */
    public function toIso8601String(): string;

    /**
     * Format the instance as RFC822
     *
     * @return string
     */
    public function toRfc822String(): string;

    /**
     * Format the instance as RFC850
     *
     * @return string
     */
    public function toRfc850String(): string;

    /**
     * Format the instance as RFC1036
     *
     * @return string
     */
    public function toRfc1036String(): string;

    /**
     * Format the instance as RFC1123
     *
     * @return string
     */
    public function toRfc1123String(): string;

    /**
     * Format the instance as RFC2822
     *
     * @return string
     */
    public function toRfc2822String(): string;

    /**
     * Format the instance as RFC3339
     *
     * @return string
     */
    public function toRfc3339String(): string;

    /**
     * Format the instance as RSS
     *
     * @return string
     */
    public function toRssString(): string;

    /**
     * Format the instance as W3C
     *
     * @return string
     */
    public function toW3cString(): string;

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @see equals
     */
    public function eq(ChronosInterface $dt): bool;

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
    public function ne(ChronosInterface $dt): bool;

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
    public function gt(ChronosInterface $dt): bool;

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
    public function gte(ChronosInterface $dt): bool;

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
    public function lt(ChronosInterface $dt): bool;

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
    public function lte(ChronosInterface $dt): bool;

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
    public function between(ChronosInterface $dt1, ChronosInterface $dt2, bool $equal = true): bool;

    /**
     * Get the closest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return static
     */
    public function closest(ChronosInterface $dt1, ChronosInterface $dt2): self;

    /**
     * Get the farthest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return static
     */
    public function farthest(ChronosInterface $dt1, ChronosInterface $dt2): self;

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return static
     */
    public function min(?ChronosInterface $dt = null): self;

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return static
     */
    public function max(?ChronosInterface $dt = null): self;

    /**
     * Determines if the instance is a weekday
     *
     * @return bool
     */
    public function isWeekday(): bool;

    /**
     * Determines if the instance is a weekend day
     *
     * @return bool
     */
    public function isWeekend(): bool;

    /**
     * Determines if the instance is yesterday
     *
     * @return bool
     */
    public function isYesterday(): bool;

    /**
     * Determines if the instance is today
     *
     * @return bool
     */
    public function isToday(): bool;

    /**
     * Determines if the instance is tomorrow
     *
     * @return bool
     */
    public function isTomorrow(): bool;

    /**
     * Determines if the instance is in the future, ie. greater (after) than now
     *
     * @return bool
     */
    public function isFuture(): bool;

    /**
     * Determines if the instance is in the past, ie. less (before) than now
     *
     * @return bool
     */
    public function isPast(): bool;

    /**
     * Determines if the instance is a leap year
     *
     * @return bool
     */
    public function isLeapYear(): bool;

    /**
     * Checks if the passed in date is the same day as the instance current day.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to check against.
     * @return bool
     */
    public function isSameDay(ChronosInterface $dt): bool;

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday(): bool;

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday(): bool;

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday(): bool;

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday(): bool;

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday(): bool;

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday(): bool;

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday(): bool;

    /**
     * Returns true if this object represents a date within the current week
     *
     * @return bool
     */
    public function isThisWeek(): bool;

    /**
     * Returns true if this object represents a date within the current month
     *
     * @return bool
     */
    public function isThisMonth(): bool;

    /**
     * Returns true if this object represents a date within the current year
     *
     * @return bool
     */
    public function isThisYear(): bool;

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
     * @return static
     */
    public function addYears(int $value): self;

    /**
     * Add a year to the instance
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYear(int $value = 1): self;

    /**
     * Remove years from the instance.
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYears(int $value): self;

    /**
     * Remove a year from the instance.
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYear(int $value = 1): self;

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
     * @return static
     */
    public function addYearsWithOverflow(int $value): self;

    /**
     * Add a year with overflow to the instance
     *
     * Has the same behavior as `addYearsWithOverflow()`.
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYearWithOverflow(int $value = 1): self;

    /**
     * Remove years with overflow from the instance
     *
     * Has the same behavior as `addYearsWithOverflow()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYearsWithOverflow(int $value): self;

    /**
     * Remove a year with overflow from the instance
     *
     * Has the same behavior as `addYearsWithOverflow()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYearWithOverflow(int $value = 1): self;

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * If the new date does not exist, the last day of the month is used
     * instead instead of overflowing into the next month.
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
    public function addMonths(int $value): self;

    /**
     * Add a month to the instance.
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonth(int $value = 1): self;

    /**
     * Remove a month from the instance
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonth(int $value = 1): self;

    /**
     * Remove months from the instance.
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonths(int $value): self;

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
     * @return static
     */
    public function addMonthsWithOverflow(int $value): self;

    /**
     * Add a month with overflow to the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthWithOverflow(int $value = 1): self;

    /**
     * Remove months with overflow from the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthsWithOverflow(int $value): self;

    /**
     * Remove a month with overflow from the instance.
     *
     * Has the same behavior as `addMonthsWithOverflow()`.
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthWithOverflow(int $value = 1): self;

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDays(int $value): self;

    /**
     * Add a day to the instance
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDay(int $value = 1): self;

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDays(int $value): self;

    /**
     * Remove a day from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDay(int $value = 1): self;

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekdays(int $value): self;

    /**
     * Add a weekday to the instance
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekday(int $value = 1): self;

    /**
     * Remove a weekday from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekday(int $value = 1): self;

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekdays(int $value): self;

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeeks(int $value): self;

    /**
     * Add a week to the instance
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeek(int $value = 1): self;

    /**
     * Remove a week from the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeek(int $value = 1): self;

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeeks(int $value): self;

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHours(int $value): self;

    /**
     * Add an hour to the instance
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHour(int $value = 1): self;

    /**
     * Remove an hour from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHour(int $value = 1): self;

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHours(int $value): self;

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinutes(int $value): self;

    /**
     * Add a minute to the instance
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinute(int $value = 1): self;

    /**
     * Remove a minute from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinute(int $value = 1): self;

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinutes(int $value): self;

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSeconds(int $value): self;

    /**
     * Add a second to the instance
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSecond(int $value = 1): self;

    /**
     * Remove a second from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSecond(int $value = 1): self;

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSeconds(int $value): self;

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
    public function diffForHumans(?ChronosInterface $other = null, bool $absolute = false): string;

    /**
     * Get the difference in years
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInYears(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in months
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in weeks
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in days
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDays(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(callable $callback, ?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(callable $callback, ?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference by the given interval using a filter callable
     *
     * @param \Cake\Chronos\ChronosInterval $ci An interval to traverse by
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffFiltered(
        ChronosInterval $ci,
        callable $callback,
        ?ChronosInterface $dt = null,
        bool $abs = true
    ): int;

    /**
     * Get the difference in weekdays
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in weekend days using a filter
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in hours
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHours(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in minutes
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * Get the difference in seconds
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(?ChronosInterface $dt = null, bool $abs = true): int;

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight(): int;

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay(): int;

    /**
     * Resets the time to 00:00:00
     *
     * @return static
     */
    public function startOfDay(): self;

    /**
     * Resets the time to 23:59:59
     *
     * @return static
     */
    public function endOfDay(): self;

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return static
     */
    public function startOfMonth(): self;

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return static
     */
    public function endOfMonth(): self;

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return static
     */
    public function startOfYear(): self;

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return static
     */
    public function endOfYear(): self;

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return static
     */
    public function startOfDecade(): self;

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return static
     */
    public function endOfDecade(): self;

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return static
     */
    public function startOfCentury(): self;

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return static
     */
    public function endOfCentury(): self;

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return static
     */
    public function startOfWeek(): self;

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59
     *
     * @return static
     */
    public function endOfWeek(): self;

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function next(?int $dayOfWeek = null);

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function previous(?int $dayOfWeek = null);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfMonth(?int $dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfMonth(?int $dayOfWeek = null);

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
    public function nthOfMonth(int $nth, int $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfQuarter(?int $dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfQuarter(?int $dayOfWeek = null);

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
    public function nthOfQuarter(int $nth, int $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfYear(?int $dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfYear(?int $dayOfWeek = null);

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
    public function nthOfYear(int $nth, int $dayOfWeek);

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function average(?ChronosInterface $dt = null): self;

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function isBirthday(ChronosInterface $dt): bool;

    /**
     * Returns true this instance happened within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function wasWithinLast($timeInterval): bool;

    /**
     * Returns true this instance will happen within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function isWithinNext($timeInterval): bool;

    /**
     * Check if instance of ChronosInterface is mutable.
     *
     * @return bool
     */
    public function isMutable(): bool;
}
