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
use Cake\Chronos\ChronosInterval;
use Cake\Chronos\DifferenceFormatter;
use Cake\Chronos\DifferenceFormatterInterface;
use DatePeriod;
use DateTimeInterface;
use DateTimeZone;

/**
 * Provides methods for getting differences between datetime objects.
 *
 * Expects that the implementing class implements:
 *
 * - static::now()
 * - static::instance()
 * - copy()
 */
trait DifferenceTrait
{
    /**
     * Instance of the diff formatting object.
     *
     * @var \Cake\Chronos\DifferenceFormatterInterface
     */
    protected static $diffFormatter;

    /**
     * Get the difference in years
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInYears(?ChronosInterface $dt = null, bool $abs = true): int
    {
        $diff = $this->diff($dt ?? static::now($this->tz), $abs);

        return $diff->invert ? -$diff->y : $diff->y;
    }

    /**
     * Get the difference in months
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(?ChronosInterface $dt = null, bool $abs = true): int
    {
        $diff = $this->diff($dt ?? static::now($this->tz), $abs);
        $months = $diff->y * ChronosInterface::MONTHS_PER_YEAR + $diff->m;

        return $diff->invert ? -$months : $months;
    }

    /**
     * Get the difference in months ignoring the timezone. This means the months are calculated
     * in the specified timezone without converting to UTC first. This prevents the day from changing
     * which can change the month.
     *
     * For example, if comparing `2019-06-01 Asia/Tokyo` and `2019-10-01 Asia/Tokyo`,
     * the result would be 4 months instead of 3 when using normal `DateTime::diff()`.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonthsIgnoreTimezone(?ChronosInterface $dt = null, bool $abs = true): int
    {
        $utcTz = new DateTimeZone('UTC');
        $source = new static($this->format('Y-m-d H:i:s.u'), $utcTz);

        $dt = $dt ?? static::now($this->tz);
        $dt = new static($dt->format('Y-m-d H:i:s.u'), $utcTz);

        return $this->diffInMonths($dt, $abs);
    }

    /**
     * Get the difference in weeks
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(?ChronosInterface $dt = null, bool $abs = true): int
    {
        return (int)($this->diffInDays($dt, $abs) / ChronosInterface::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDays(?ChronosInterface $dt = null, bool $abs = true): int
    {
        $diff = $this->diff($dt ?? static::now($this->tz), $abs);

        return $diff->invert ? -$diff->days : $diff->days;
    }

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(callable $callback, ?ChronosInterface $dt = null, bool $abs = true): int
    {
        return $this->diffFiltered(ChronosInterval::day(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(callable $callback, ?ChronosInterface $dt = null, bool $abs = true): int
    {
        return $this->diffFiltered(ChronosInterval::hour(), $callback, $dt, $abs);
    }

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
    ): int {
        $start = $this;
        $end = $dt ?? static::now($this->tz);
        $inverse = false;

        if ($end < $start) {
            $start = $end;
            $end = $this;
            $inverse = true;
        }

        $period = new DatePeriod($start, $ci, $end);
        $vals = array_filter(iterator_to_array($period), function (DateTimeInterface $date) use ($callback) {
            return $callback(static::instance($date));
        });

        $diff = count($vals);

        return $inverse && !$abs ? -$diff : $diff;
    }

    /**
     * Get the difference in weekdays
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(?ChronosInterface $dt = null, bool $abs = true): int
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekday();
        }, $dt, $abs);
    }

    /**
     * Get the difference in weekend days using a filter
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(?ChronosInterface $dt = null, bool $abs = true): int
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekend();
        }, $dt, $abs);
    }

    /**
     * Get the difference in hours
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHours(?ChronosInterface $dt = null, bool $abs = true): int
    {
        return (int)(
            $this->diffInSeconds($dt, $abs)
            / ChronosInterface::SECONDS_PER_MINUTE
            / ChronosInterface::MINUTES_PER_HOUR
        );
    }

    /**
     * Get the difference in minutes
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(?ChronosInterface $dt = null, bool $abs = true): int
    {
        return (int)($this->diffInSeconds($dt, $abs) / ChronosInterface::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(?ChronosInterface $dt = null, bool $abs = true): int
    {
        $dt = $dt ?? static::now($this->tz);
        $value = $dt->getTimestamp() - $this->getTimestamp();

        return $abs ? abs($value) : $value;
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight(): int
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay(): int
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }

    /**
     * Convenience method for getting the remaining time from a given time.
     *
     * @param \DateTime|\DateTimeImmutable $datetime The date to get the remaining time from.
     * @return \DateInterval|bool The DateInterval object representing the difference between the two dates or FALSE on failure.
     */
    public static function fromNow($datetime)
    {
        $timeNow = new static();

        return $timeNow->diff($datetime);
    }

    /**
     * Get the difference in a human readable format.
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
     * @param bool $absolute removes time difference modifiers ago, after, etc
     * @return string
     */
    public function diffForHumans(?ChronosInterface $other = null, bool $absolute = false): string
    {
        return static::diffFormatter()->diffForHumans($this, $other, $absolute);
    }

    /**
     * Get the difference formatter instance or overwrite the current one.
     *
     * @param \Cake\Chronos\DifferenceFormatterInterface|null $formatter The formatter instance when setting.
     * @return \Cake\Chronos\DifferenceFormatterInterface The formatter instance.
     */
    public static function diffFormatter(?DifferenceFormatterInterface $formatter = null): DifferenceFormatterInterface
    {
        if ($formatter === null) {
            if (static::$diffFormatter === null) {
                static::$diffFormatter = new DifferenceFormatter();
            }

            return static::$diffFormatter;
        }

        return static::$diffFormatter = $formatter;
    }
}
