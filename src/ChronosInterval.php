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

use DateInterval;
use InvalidArgumentException;

/**
 * A simple API extension for DateInterval.
 * The implementation provides helpers to handle weeks but only days are saved.
 * Weeks are calculated based on the total days of the current instance.
 *
 * @property int $years Total years of the current interval.
 * @property int $months Total months of the current interval.
 * @property int $weeks Total weeks of the current interval calculated from the days.
 * @property int $dayz Total days of the current interval (weeks * 7 + days).
 * @property int $hours Total hours of the current interval.
 * @property int $minutes Total minutes of the current interval.
 * @property int $seconds Total seconds of the current interval.
 *
 * @property-read int $dayzExcludeWeeks Total days remaining in the final week of the current instance (days % 7).
 * @property-read int $daysExcludeWeeks alias of dayzExcludeWeeks
 *
 * @method static ChronosInterval years($years = 1) Create instance specifying a number of years.
 * @method static ChronosInterval year($years = 1) Alias for years
 * @method static ChronosInterval months($months = 1) Create instance specifying a number of months.
 * @method static ChronosInterval month($months = 1) Alias for months
 * @method static ChronosInterval weeks($weeks = 1) Create instance specifying a number of weeks.
 * @method static ChronosInterval week($weeks = 1) Alias for weeks
 * @method static ChronosInterval days($days = 1) Create instance specifying a number of days.
 * @method static ChronosInterval dayz($days = 1) Alias for days
 * @method static ChronosInterval day($days = 1) Alias for days
 * @method static ChronosInterval hours($hours = 1) Create instance specifying a number of hours.
 * @method static ChronosInterval hour($hours = 1) Alias for hours
 * @method static ChronosInterval minutes($minutes = 1) Create instance specifying a number of minutes.
 * @method static ChronosInterval minute($minutes = 1) Alias for minutes
 * @method static ChronosInterval seconds($seconds = 1) Create instance specifying a number of seconds.
 * @method static ChronosInterval second($seconds = 1) Alias for seconds
 *
 * @method ChronosInterval years() years($years = 1) Set the years portion of the current interval.
 * @method ChronosInterval year() year($years = 1) Alias for years.
 * @method ChronosInterval months() months($months = 1) Set the months portion of the current interval.
 * @method ChronosInterval month() month($months = 1) Alias for months.
 * @method ChronosInterval weeks() weeks($weeks = 1) Set the weeks portion of the current interval.  Will overwrite dayz value.
 * @method ChronosInterval week() week($weeks = 1) Alias for weeks.
 * @method ChronosInterval days() days($days = 1) Set the days portion of the current interval.
 * @method ChronosInterval dayz() dayz($days = 1) Alias for days.
 * @method ChronosInterval day() day($days = 1) Alias for days.
 * @method ChronosInterval hours() hours($hours = 1) Set the hours portion of the current interval.
 * @method ChronosInterval hour() hour($hours = 1) Alias for hours.
 * @method ChronosInterval minutes() minutes($minutes = 1) Set the minutes portion of the current interval.
 * @method ChronosInterval minute() minute($minutes = 1) Alias for minutes.
 * @method ChronosInterval seconds() seconds($seconds = 1) Set the seconds portion of the current interval.
 * @method ChronosInterval second() second($seconds = 1) Alias for seconds.
 */
class ChronosInterval extends DateInterval
{
    /**
     * Interval spec period designators
     */
    const PERIOD_PREFIX = 'P';
    const PERIOD_YEARS = 'Y';
    const PERIOD_MONTHS = 'M';
    const PERIOD_DAYS = 'D';
    const PERIOD_TIME_PREFIX = 'T';
    const PERIOD_HOURS = 'H';
    const PERIOD_MINUTES = 'M';
    const PERIOD_SECONDS = 'S';

    /**
     * Before PHP 5.4.20/5.5.4 instead of `false` days will be set to -99999 when the interval instance
     * was created by DateTime:diff().
     */
    const PHP_DAYS_FALSE = -99999;

    /**
     * Whether or not this object was created in HHVM
     *
     * @var bool
     */
    protected $isHHVM = false;

    /**
     * Determine if the interval was created via DateTime:diff() or not.
     *
     * @param \DateInterval $interval The interval to check.
     * @return bool
     */
    protected static function wasCreatedFromDiff(DateInterval $interval)
    {
        return ($interval->days !== false && $interval->days !== static::PHP_DAYS_FALSE);
    }

    /**
     * Create a new ChronosInterval instance.
     *
     * @param int|null $years The year to use.
     * @param int|null $months The month to use.
     * @param int|null $weeks The week to use.
     * @param int|null $days The day to use.
     * @param int|null $hours The hours to use.
     * @param int|null $minutes The minutes to use.
     * @param int|null $seconds The seconds to use.
     */
    public function __construct($years = 1, $months = null, $weeks = null, $days = null, $hours = null, $minutes = null, $seconds = null)
    {
        $this->isHHVM = defined('HHVM_VERSION');
        $spec = static::PERIOD_PREFIX;

        $spec .= $years > 0 ? $years . static::PERIOD_YEARS : '';
        $spec .= $months > 0 ? $months . static::PERIOD_MONTHS : '';

        $specDays = 0;
        $specDays += $weeks > 0 ? $weeks * ChronosInterface::DAYS_PER_WEEK : 0;
        $specDays += $days > 0 ? $days : 0;

        $spec .= ($specDays > 0) ? $specDays . static::PERIOD_DAYS : '';

        if ($spec === static::PERIOD_PREFIX) {
            $spec .= '0' . static::PERIOD_YEARS;
        }

        if ($hours > 0 || $minutes > 0 || $seconds > 0) {
            $spec .= static::PERIOD_TIME_PREFIX;
            $spec .= $hours > 0 ? $hours . static::PERIOD_HOURS : '';
            $spec .= $minutes > 0 ? $minutes . static::PERIOD_MINUTES : '';
            $spec .= $seconds > 0 ? $seconds . static::PERIOD_SECONDS : '';
        }

        parent::__construct($spec);
    }

    /**
     * Create a new ChronosInterval instance from specific values.
     * This is an alias for the constructor that allows better fluent
     * syntax as it allows you to do ChronosInterval::create(1)->fn() rather than
     * (new ChronosInterval(1))->fn().
     *
     * @param int|null $years The year to use.
     * @param int|null $months The month to use.
     * @param int|null $weeks The week to use.
     * @param int|null $days The day to use.
     * @param int|null $hours The hours to use.
     * @param int|null $minutes The minutes to use.
     * @param int|null $seconds The seconds to use.
     * @return static
     */
    public static function create($years = 1, $months = null, $weeks = null, $days = null, $hours = null, $minutes = null, $seconds = null)
    {
        return new static($years, $months, $weeks, $days, $hours, $minutes, $seconds);
    }

    /**
     * Provide static helpers to create instances. Allows:
     *
     * ```
     * ChronosInterval::years(3)
     * // or
     * ChronosInterval::month(1);
     * ```
     *
     * Note: This is done using the magic method to allow static and instance methods to
     *       have the same names.
     *
     * @param string $name The property to configure. Accepts singular and plural forms.
     * @param array $args Contains the value to use.
     * @return static
     */
    public static function __callStatic($name, $args)
    {
        $arg = count($args) === 0 ? 1 : $args[0];

        switch ($name) {
            case 'years':
            case 'year':
                return new static($arg);

            case 'months':
            case 'month':
                return new static(null, $arg);

            case 'weeks':
            case 'week':
                return new static(null, null, $arg);

            case 'days':
            case 'dayz':
            case 'day':
                return new static(null, null, null, $arg);

            case 'hours':
            case 'hour':
                return new static(null, null, null, null, $arg);

            case 'minutes':
            case 'minute':
                return new static(null, null, null, null, null, $arg);

            case 'seconds':
            case 'second':
                return new static(null, null, null, null, null, null, $arg);
        }
    }

    /**
     * Create a ChronosInterval instance from a DateInterval one.  Can not instance
     * DateInterval objects created from DateTime::diff() as you can't externally
     * set the $days field.
     *
     * @param \DateInterval $di The DateInterval instance to copy.
     * @throws \InvalidArgumentException
     * @return static
     */
    public static function instance(DateInterval $di)
    {
        if (static::wasCreatedFromDiff($di)) {
            throw new InvalidArgumentException(
                "Can not instance a DateInterval object created from DateTime::diff()."
            );
        }

        $instance = new static($di->y, $di->m, 0, $di->d, $di->h, $di->i, $di->s);
        $instance->invert = $di->invert;
        $instance->days = $di->days;

        return $instance;
    }

    /**
     * Get a part of the ChronosInterval object
     *
     * @param string $name The property to read.
     * @throws \InvalidArgumentException
     * @return int
     */
    public function __get($name)
    {
        switch ($name) {
            case 'years':
                return $this->isHHVM ? parent::__get('y') : $this->y;

            case 'months':
                return $this->isHHVM ? parent::__get('m') : $this->m;

            case 'dayz':
                return $this->isHHVM ? parent::__get('d') : $this->d;

            case 'hours':
                return $this->isHHVM ? parent::__get('h') : $this->h;

            case 'minutes':
                return $this->isHHVM ? parent::__get('i') : $this->i;

            case 'seconds':
                return $this->isHHVM ? parent::__get('s') : $this->s;

            case 'weeks':
                return (int)floor(($this->isHHVM ? parent::__get('d') : $this->d) / ChronosInterface::DAYS_PER_WEEK);

            case 'daysExcludeWeeks':
            case 'dayzExcludeWeeks':
                return $this->dayz % ChronosInterface::DAYS_PER_WEEK;
            case 'days':
                return $this->isHHVM ? parent::__get('days') : $this->days;
            case 'y':
            case 'm':
            case 'd':
            case 'h':
            case 'i':
            case 's':
            case 'invert':
                return parent::__get($name);
            default:
                throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $name));
        }
    }

    /**
     * Set a part of the ChronosInterval object
     *
     * @param string $name The property to augment.
     * @param int $val The value to change.
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __set($name, $val)
    {
        switch ($name) {
            case 'years':
                $this->isHHVM ? parent::__set('y', $val) : $this->y = $val;
                break;

            case 'months':
                $this->isHHVM ? parent::__set('m', $val) : $this->m = $val;
                break;

            case 'weeks':
                $val = $val * ChronosInterface::DAYS_PER_WEEK;
                $this->isHHVM ? parent::__set('d', $val) : $this->d = $val;
                break;

            case 'dayz':
                $this->isHHVM ? parent::__set('d', $val) : $this->d = $val;
                break;

            case 'hours':
                $this->isHHVM ? parent::__set('h', $val) : $this->h = $val;
                break;

            case 'minutes':
                $this->isHHVM ? parent::__set('i', $val) : $this->i = $val;
                break;

            case 'seconds':
                $this->isHHVM ? parent::__set('s', $val) : $this->s = $val;
                break;

            case 'invert':
                $this->isHHVM ? parent::__set('invert', $val) : $this->invert = $val;
                break;
            default:
                if ($this->isHHVM) {
                    parent::__set($name, $val);
                }
        }
    }

    /**
     * Allow setting of weeks and days to be cumulative.
     *
     * @param int $weeks Number of weeks to set
     * @param int $days Number of days to set
     * @return static
     */
    public function weeksAndDays($weeks, $days)
    {
        $this->dayz = ($weeks * ChronosInterface::DAYS_PER_WEEK) + $days;

        return $this;
    }

    /**
     * Allow fluent calls on the setters... ChronosInterval::years(3)->months(5)->day().
     *
     * Note: This is done using the magic method to allow static and instance methods to
     *       have the same names.
     *
     * @param string $name The property name to augment. Accepts plural forms in addition
     *   to singular ones.
     * @param array $args The value to set.
     * @return static
     */
    public function __call($name, $args)
    {
        $arg = count($args) === 0 ? 1 : $args[0];

        switch ($name) {
            case 'years':
            case 'year':
                $this->years = $arg;
                break;

            case 'months':
            case 'month':
                $this->months = $arg;
                break;

            case 'weeks':
            case 'week':
                $this->dayz = $arg * ChronosInterface::DAYS_PER_WEEK;
                break;

            case 'days':
            case 'dayz':
            case 'day':
                $this->dayz = $arg;
                break;

            case 'hours':
            case 'hour':
                $this->hours = $arg;
                break;

            case 'minutes':
            case 'minute':
                $this->minutes = $arg;
                break;

            case 'seconds':
            case 'second':
                $this->seconds = $arg;
                break;
        }

        return $this;
    }

    /**
     * Add the passed interval to the current instance
     *
     * @param \DateInterval $interval The interval to add.
     * @return static
     */
    public function add(DateInterval $interval)
    {
        $sign = ($interval->invert === 1) ? -1 : 1;

        if (static::wasCreatedFromDiff($interval)) {
            $this->dayz = $this->dayz + ($interval->days * $sign);
        } else {
            $this->years = $this->years + ($interval->y * $sign);
            $this->months = $this->months + ($interval->m * $sign);
            $this->dayz = $this->dayz + ($interval->d * $sign);
            $this->hours = $this->hours + ($interval->h * $sign);
            $this->minutes = $this->minutes + ($interval->i * $sign);
            $this->seconds = $this->seconds + ($interval->s * $sign);
        }

        return $this;
    }

    /**
     * Returns the ISO 8601 interval string.
     *
     * @return string Interval as string representation
     */
    public function __toString()
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
