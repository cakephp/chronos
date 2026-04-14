<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

use DateInterval;
use InvalidArgumentException;
use Stringable;

/**
 * A wrapper around DateInterval that provides additional convenience methods.
 *
 * This class uses the decorator pattern to wrap a native DateInterval,
 * providing ISO 8601 duration string formatting and factory methods.
 *
 * @property-read int $y Years
 * @property-read int $m Months
 * @property-read int $d Days
 * @property-read int $h Hours
 * @property-read int $i Minutes
 * @property-read int $s Seconds
 * @property-read float $f Microseconds as a fraction of a second
 * @property-read int $invert 1 if the interval is negative
 * @property-read int|false $days Total days if created from diff(), false otherwise
 * @phpstan-consistent-constructor
 */
class ChronosInterval implements Stringable
{
    /**
     * The wrapped DateInterval instance.
     */
    protected DateInterval $interval;

    /**
     * Create a new ChronosInterval instance.
     *
     * @param \DateInterval $interval The interval to wrap.
     */
    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * Create an interval from a specification string.
     *
     * @param string $spec An interval specification (e.g., 'P1Y2M3D').
     * @return static
     */
    public static function create(string $spec): static
    {
        return new static(new DateInterval($spec));
    }

    /**
     * Create an interval from individual components.
     *
     * @param int|null $years Years
     * @param int|null $months Months
     * @param int|null $weeks Weeks (converted to days)
     * @param int|null $days Days
     * @param int|null $hours Hours
     * @param int|null $minutes Minutes
     * @param int|null $seconds Seconds
     * @param int|null $microseconds Microseconds
     * @return static
     */
    public static function createFromValues(
        ?int $years = null,
        ?int $months = null,
        ?int $weeks = null,
        ?int $days = null,
        ?int $hours = null,
        ?int $minutes = null,
        ?int $seconds = null,
        ?int $microseconds = null,
    ): static {
        $interval = Chronos::createInterval(
            $years,
            $months,
            $weeks,
            $days,
            $hours,
            $minutes,
            $seconds,
            $microseconds,
        );

        return new static($interval);
    }

    /**
     * Create an interval from a DateInterval instance.
     *
     * @param \DateInterval $interval The interval to wrap.
     * @return static
     */
    public static function instance(DateInterval $interval): static
    {
        return new static($interval);
    }

    /**
     * Create an interval from a relative date string.
     *
     * This wraps DateInterval::createFromDateString() which accepts
     * relative date/time formats like "1 year + 2 days" or "3 months".
     *
     * @param string $datetime A relative date/time string.
     * @return static
     * @throws \InvalidArgumentException If the string cannot be parsed.
     * @see https://www.php.net/manual/en/dateinterval.createfromdatestring.php
     */
    public static function createFromDateString(string $datetime): static
    {
        $interval = DateInterval::createFromDateString($datetime);
        if ($interval === false) {
            throw new InvalidArgumentException('Unable to parse interval string: ' . $datetime);
        }

        return new static($interval);
    }

    /**
     * Get the underlying DateInterval instance.
     *
     * Use this when you need to pass the interval to code that expects
     * a native DateInterval.
     *
     * @return \DateInterval
     */
    public function toNative(): DateInterval
    {
        return $this->interval;
    }

    /**
     * Format the interval as an ISO 8601 duration string.
     *
     * @return string
     */
    public function toIso8601String(): string
    {
        $spec = 'P';

        if ($this->interval->y) {
            $spec .= $this->interval->y . 'Y';
        }
        if ($this->interval->m) {
            $spec .= $this->interval->m . 'M';
        }
        if ($this->interval->d) {
            $spec .= $this->interval->d . 'D';
        }

        if ($this->interval->h || $this->interval->i || $this->interval->s || $this->interval->f) {
            $spec .= 'T';

            if ($this->interval->h) {
                $spec .= $this->interval->h . 'H';
            }
            if ($this->interval->i) {
                $spec .= $this->interval->i . 'M';
            }
            if ($this->interval->s || $this->interval->f) {
                $seconds = (string)$this->interval->s;
                if ($this->interval->f) {
                    $fraction = rtrim(sprintf('%06d', (int)($this->interval->f * 1000000)), '0');
                    if ($fraction !== '') {
                        $seconds .= '.' . $fraction;
                    }
                }
                $spec .= $seconds . 'S';
            }
        }

        // Handle empty interval
        if ($spec === 'P') {
            $spec = 'PT0S';
        }

        return ($this->interval->invert ? '-' : '') . $spec;
    }

    /**
     * Format the interval using DateInterval::format().
     *
     * @param string $format The format string.
     * @return string
     * @see https://www.php.net/manual/en/dateinterval.format.php
     */
    public function format(string $format): string
    {
        return $this->interval->format($format);
    }

    /**
     * Get the total number of seconds in the interval.
     *
     * Note: This calculation assumes 30 days per month and 365 days per year,
     * which is an approximation. For precise calculations, use diff() between
     * specific dates.
     *
     * @return int
     */
    public function totalSeconds(): int
    {
        $seconds = $this->interval->s;
        $seconds += $this->interval->i * 60;
        $seconds += $this->interval->h * 3600;
        $seconds += $this->interval->d * 86400;
        $seconds += $this->interval->m * 30 * 86400;
        $seconds += $this->interval->y * 365 * 86400;

        return $this->interval->invert ? -$seconds : $seconds;
    }

    /**
     * Get the total number of days in the interval.
     *
     * If the interval was created from a diff(), this returns the exact
     * total days. Otherwise, it approximates using 30 days per month
     * and 365 days per year.
     *
     * @return int
     */
    public function totalDays(): int
    {
        if ($this->interval->days !== false) {
            return $this->interval->invert ? -$this->interval->days : $this->interval->days;
        }

        $days = $this->interval->d;
        $days += $this->interval->m * 30;
        $days += $this->interval->y * 365;

        return $this->interval->invert ? -$days : $days;
    }

    /**
     * Check if this interval is negative.
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->interval->invert === 1;
    }

    /**
     * Check if this interval is zero (no duration).
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->interval->y === 0
            && $this->interval->m === 0
            && $this->interval->d === 0
            && $this->interval->h === 0
            && $this->interval->i === 0
            && $this->interval->s === 0
            && $this->interval->f === 0.0;
    }

    /**
     * Add another interval to this one.
     *
     * Returns a new ChronosInterval with the combined values.
     * Note: This performs simple addition of each component and does not
     * normalize overflow (e.g., 70 minutes stays as 70 minutes).
     *
     * @param \DateInterval|\Cake\Chronos\ChronosInterval $interval The interval to add.
     * @return static
     */
    public function add(DateInterval|ChronosInterval $interval): static
    {
        if ($interval instanceof ChronosInterval) {
            $interval = $interval->toNative();
        }

        $result = new DateInterval('P0D');
        $result->y = $this->interval->y + $interval->y;
        $result->m = $this->interval->m + $interval->m;
        $result->d = $this->interval->d + $interval->d;
        $result->h = $this->interval->h + $interval->h;
        $result->i = $this->interval->i + $interval->i;
        $result->s = $this->interval->s + $interval->s;
        $result->f = $this->interval->f + $interval->f;

        return new static($result);
    }

    /**
     * Subtract another interval from this one.
     *
     * Returns a new ChronosInterval with the subtracted values.
     * Note: This performs simple subtraction of each component. If any
     * component becomes negative, the result may be unexpected.
     *
     * @param \DateInterval|\Cake\Chronos\ChronosInterval $interval The interval to subtract.
     * @return static
     */
    public function sub(DateInterval|ChronosInterval $interval): static
    {
        if ($interval instanceof ChronosInterval) {
            $interval = $interval->toNative();
        }

        $result = new DateInterval('P0D');
        $result->y = $this->interval->y - $interval->y;
        $result->m = $this->interval->m - $interval->m;
        $result->d = $this->interval->d - $interval->d;
        $result->h = $this->interval->h - $interval->h;
        $result->i = $this->interval->i - $interval->i;
        $result->s = $this->interval->s - $interval->s;
        $result->f = $this->interval->f - $interval->f;

        return new static($result);
    }

    /**
     * Format the interval as a strtotime()-compatible string.
     *
     * Returns a relative date/time string that can be used with strtotime()
     * or DateInterval::createFromDateString().
     *
     * @return string
     */
    public function toDateString(): string
    {
        $parts = [];

        if ($this->interval->y) {
            $parts[] = $this->interval->y . ' ' . ($this->interval->y === 1 ? 'year' : 'years');
        }
        if ($this->interval->m) {
            $parts[] = $this->interval->m . ' ' . ($this->interval->m === 1 ? 'month' : 'months');
        }
        if ($this->interval->d) {
            $parts[] = $this->interval->d . ' ' . ($this->interval->d === 1 ? 'day' : 'days');
        }
        if ($this->interval->h) {
            $parts[] = $this->interval->h . ' ' . ($this->interval->h === 1 ? 'hour' : 'hours');
        }
        if ($this->interval->i) {
            $parts[] = $this->interval->i . ' ' . ($this->interval->i === 1 ? 'minute' : 'minutes');
        }
        if ($this->interval->s) {
            $parts[] = $this->interval->s . ' ' . ($this->interval->s === 1 ? 'second' : 'seconds');
        }

        if ($parts === []) {
            return '0 seconds';
        }

        $result = implode(' ', $parts);

        return $this->interval->invert ? '-' . $result : $result;
    }

    /**
     * Return the interval as an ISO 8601 duration string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toIso8601String();
    }

    /**
     * Allow read access to DateInterval properties.
     *
     * @param string $name Property name.
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->interval->{$name};
    }

    /**
     * Check if a DateInterval property exists.
     *
     * @param string $name Property name.
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->interval->{$name});
    }

    /**
     * Debug info.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'interval' => $this->toIso8601String(),
            'years' => $this->interval->y,
            'months' => $this->interval->m,
            'days' => $this->interval->d,
            'hours' => $this->interval->h,
            'minutes' => $this->interval->i,
            'seconds' => $this->interval->s,
            'microseconds' => $this->interval->f,
            'invert' => $this->interval->invert,
        ];
    }
}
