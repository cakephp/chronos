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
namespace Cake\Chronos;

use DateInterval;

/**
 * A simple API extension for DateInterval.
 *
 * Provides a static conveninence methods for creating date intervals.
 */
class ChronosInterval extends DateInterval
{
    /**
     * @var string
     */
    public const PERIOD_PREFIX = 'P';
    /**
     * @var string
     */
    public const PERIOD_YEARS = 'Y';
    /**
     * @var string
     */
    public const PERIOD_MONTHS = 'M';
    /**
     * @var string
     */
    public const PERIOD_WEEKS = 'W';
    /**
     * @var string
     */
    public const PERIOD_DAYS = 'D';
    /**
     * @var string
     */
    public const PERIOD_TIME_PREFIX = 'T';
    /**
     * @var string
     */
    public const PERIOD_HOURS = 'H';
    /**
     * @var string
     */
    public const PERIOD_MINUTES = 'M';
    /**
     * @var string
     */
    public const PERIOD_SECONDS = 'S';

    /**
     * Create a new ChronosInterval instance from specific values.
     *
     * @param int|null $years The year to use.
     * @param int|null $months The month to use.
     * @param int|null $weeks The week to use.
     * @param int|null $days The day to use.
     * @param int|null $hours The hours to use.
     * @param int|null $minutes The minutes to use.
     * @param int|null $seconds The seconds to use.
     * @param int|null $microseconds The microseconds to use.
     * @return static
     */
    public static function create(
        ?int $years = null,
        ?int $months = null,
        ?int $weeks = null,
        ?int $days = null,
        ?int $hours = null,
        ?int $minutes = null,
        ?int $seconds = null,
        ?int $microseconds = null,
    ): static {
        $spec = static::PERIOD_PREFIX;

        if ($years) {
            $spec .= $years . static::PERIOD_YEARS;
        }
        if ($months) {
            $spec .= $months . static::PERIOD_MONTHS;
        }
        if ($weeks) {
            $spec .= $weeks . static::PERIOD_WEEKS;
        }
        if ($days) {
            $spec .= $days . static::PERIOD_DAYS;
        }

        if ($hours || $minutes || $seconds) {
            $spec .= static::PERIOD_TIME_PREFIX;
            if ($hours) {
                $spec .= $hours . static::PERIOD_HOURS;
            }
            if ($minutes) {
                $spec .= $minutes . static::PERIOD_MINUTES;
            }
            if ($seconds) {
                $spec .= $seconds . static::PERIOD_SECONDS;
            }
        }

        $instance = new static($spec);

        if ($microseconds) {
            $instance->f = $microseconds / 1000000;
        }

        return $instance;
    }

    /**
     * Returns the ISO 8601 interval string.
     *
     * Caveat: The string doesn't include microseconds as it could be passed
     * to `DateInterval`'s constructor which doesn't support interval strings
     * with fractional seconds.
     *
     * @return string Interval as string representation.
     */
    public function __toString(): string
    {
        // equivalence
        $oneMinuteInSeconds = 60;
        $oneHourInSeconds = $oneMinuteInSeconds * 60;
        $oneDayInSeconds = $oneHourInSeconds * 24;
        $oneMonthInDays = 365 / 12;
        $oneMonthInSeconds = $oneDayInSeconds * $oneMonthInDays;
        $oneYearInSeconds = 12 * $oneMonthInSeconds;

        // convert
        $ySecs = $this->y * $oneYearInSeconds;
        $mSecs = $this->m * $oneMonthInSeconds;
        $dSecs = $this->d * $oneDayInSeconds;
        $hSecs = $this->h * $oneHourInSeconds;
        $iSecs = $this->i * $oneMinuteInSeconds;
        $sSecs = $this->s;

        $totalSecs = $ySecs + $mSecs + $dSecs + $hSecs + $iSecs + $sSecs;

        $y = null;
        $m = null;
        $d = null;
        $h = null;
        $i = null;

        // years
        if ($totalSecs >= $oneYearInSeconds) {
            $y = floor($totalSecs / $oneYearInSeconds);
            $totalSecs = $totalSecs - $y * $oneYearInSeconds;
        }

        // months
        if ($totalSecs >= $oneMonthInSeconds) {
            $m = floor($totalSecs / $oneMonthInSeconds);
            $totalSecs = $totalSecs - $m * $oneMonthInSeconds;
        }

        // days
        if ($totalSecs >= $oneDayInSeconds) {
            $d = floor($totalSecs / $oneDayInSeconds);
            $totalSecs = $totalSecs - $d * $oneDayInSeconds;
        }

        // hours
        if ($totalSecs >= $oneHourInSeconds) {
            $h = floor($totalSecs / $oneHourInSeconds);
            $totalSecs = $totalSecs - $h * $oneHourInSeconds;
        }

        // minutes
        if ($totalSecs >= $oneMinuteInSeconds) {
            $i = floor($totalSecs / $oneMinuteInSeconds);
            $totalSecs = $totalSecs - $i * $oneMinuteInSeconds;
        }

        $s = $totalSecs;

        $date = array_filter([
            static::PERIOD_YEARS => $y,
            static::PERIOD_MONTHS => $m,
            static::PERIOD_DAYS => $d,
        ]);

        $time = array_filter([
            static::PERIOD_HOURS => $h,
            static::PERIOD_MINUTES => $i,
            static::PERIOD_SECONDS => $s,
        ]);

        $specString = static::PERIOD_PREFIX;

        foreach ($date as $key => $value) {
            $specString .= $value . $key;
        }

        if (count($time) > 0) {
            $specString .= static::PERIOD_TIME_PREFIX;
            foreach ($time as $key => $value) {
                $specString .= $value . $key;
            }
        }

        if ($specString === static::PERIOD_PREFIX) {
            return 'PT0S';
        }

        return $this->invert === 1 ? '-' . $specString : $specString;
    }
}
