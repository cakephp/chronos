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

use DatePeriod;
use DateTimeInterface;

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
     * Get the difference in years
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInYears(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return (int)$this->diff($dt, $abs)->format('%r%y');
    }

    /**
     * Get the difference in months
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return $this->diffInYears($dt, $abs) * ChronosInterface::MONTHS_PER_YEAR + (int)$this->diff($dt, $abs)->format('%r%m');
    }

    /**
     * Get the difference in weeks
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInDays($dt, $abs) / ChronosInterface::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDays(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return (int)$this->diff($dt, $abs)->format('%r%a');
    }

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffFiltered(ChronosInterval::day(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffFiltered(ChronosInterval::hour(), $callback, $dt, $abs);
    }

    /**
     * Get the difference by the given interval using a filter callable
     *
     * @param ChronosInterval $ci An interval to traverse by
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffFiltered(ChronosInterval $ci, callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        $start = $this;
        $end = $dt === null ? static::now($this->tz) : $dt;
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
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekday();
        }, $dt, $abs);
    }

    /**
     * Get the difference in weekend days using a filter
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekend();
        }, $dt, $abs);
    }

    /**
     * Get the difference in hours
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHours(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInSeconds($dt, $abs) / ChronosInterface::SECONDS_PER_MINUTE / ChronosInterface::MINUTES_PER_HOUR);
    }

    /**
     * Get the difference in minutes
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInSeconds($dt, $abs) / ChronosInterface::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(ChronosInterface $dt = null, $abs = true)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;
        $value = $dt->getTimestamp() - $this->getTimestamp();

        return $abs ? abs($value) : $value;
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight()
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    /**
     * The number of seconds until 23:23:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay()
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }
}
