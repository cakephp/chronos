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

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
class ChronosTime
{
    /**
     * @var int
     */
    protected const TICKS_PER_MICROSECOND = 1;

    /**
     * @var int
     */
    protected const TICKS_PER_SECOND = 1000000;

    /**
     * @var int
     */
    protected const TICKS_PER_MINUTE = self::TICKS_PER_SECOND * 60;

    /**
     * @var int
     */
    protected const TICKS_PER_HOUR = self::TICKS_PER_MINUTE * 60;

    /**
     * @var int
     */
    protected const TICKS_PER_DAY = self::TICKS_PER_HOUR * 24;

    /**
     * @var int
     */
    protected int $ticks;

    /**
     * Copies time from onther instance or from time string in the format HH[:.]mm or HH[:.]mm[:.]ss.u.
     *
     * Defaults to server time.
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosTime|\DateTimeInterface|string|null $time Time
     * @param \DateTimeZone|string|null $timezone The timezone to use for now
     */
    public function __construct(
        Chronos|ChronosTime|DateTimeInterface|string|null $time = null,
        DateTimeZone|string|null $timezone = null
    ) {
        if ($time === null) {
            $time = Chronos::getTestNow() ?? Chronos::now();
            if ($timezone !== null) {
                $time = $time->setTimezone($timezone);
            }
            $this->ticks = static::parseString($time->format('H:i:s.u'));
        } elseif (is_string($time)) {
            $this->ticks = static::parseString($time);
        } elseif ($time instanceof ChronosTime) {
            $this->ticks = $time->ticks;
        } else {
            $this->ticks = static::parseString($time->format('H:i:s.u'));
        }
    }

    /**
     * Copies time from onther instance or from string in the format HH[:.]mm or HH[:.]mm[:.]ss.u
     *
     * Defaults to server time.
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosTime|\DateTimeInterface|string $time Time
     * @param \DateTimeZone|string|null $timezone The timezone to use for now
     * @return static
     */
    public static function parse(
        Chronos|ChronosTime|DateTimeInterface|string|null $time = null,
        DateTimeZone|string|null $timezone = null
    ): static {
        return new static($time, $timezone);
    }

    /**
     * @param string $time Time string in the format HH[:.]mm or HH[:.]mm[:.]ss.u
     * @return int
     */
    protected static function parseString(string $time): int
    {
        if (!preg_match('/^\s*(\d{1,2})[:.](\d{1,2})(?|[:.](\d{1,2})[.](\d+)|[:.](\d{1,2}))?\s*$/', $time, $matches)) {
            throw new InvalidArgumentException(
                'Time string is not in expected format: "HH[:.]mm" or "HH[:.]mm[:.]ss.u".'
            );
        }

        $hours = (int)$matches[1];
        $minutes = (int)$matches[2];
        $seconds = (int)($matches[3] ?? 0);
        $microseconds = (int)substr($matches[4] ?? '', 0, 6);

        if ($hours > 24 || $minutes > 59 || $seconds > 59 || $microseconds > 999_999) {
            throw new InvalidArgumentException('Time string contains invalid values.');
        }

        $ticks = $hours * self::TICKS_PER_HOUR;
        $ticks += $minutes * self::TICKS_PER_MINUTE;
        $ticks += $seconds * self::TICKS_PER_SECOND;
        $ticks += $microseconds * self::TICKS_PER_MICROSECOND;

        return $ticks % self::TICKS_PER_DAY;
    }

    /**
     * Returns instance set to server time.
     *
     * @param \DateTimeZone|string|null $timezone The timezone to use for now
     * @return static
     */
    public static function now(DateTimeZone|string|null $timezone = null): static
    {
        return new static(null, $timezone);
    }

    /**
     * Returns instance set to midnight.
     *
     * @return static
     */
    public static function midnight(): static
    {
        return new static('00:00:00');
    }

    /**
     * Returns instance set to noon.
     *
     * @return static
     */
    public static function noon(): static
    {
        return new static('12:00:00');
    }

    /**
     * Returns clock microseconds.
     *
     * @return int
     */
    public function getMicroseconds(): int
    {
        return intdiv($this->ticks % self::TICKS_PER_SECOND, self::TICKS_PER_MICROSECOND);
    }

    /**
     * Sets clock microseconds.
     *
     * @param int $microseconds Clock microseconds
     * @return static
     */
    public function setMicroseconds(int $microseconds): static
    {
        $baseTicks = $this->ticks - $this->ticks % self::TICKS_PER_SECOND;
        $newTicks = static::mod($baseTicks + $microseconds * self::TICKS_PER_MICROSECOND, self::TICKS_PER_DAY);

        $clone = clone $this;
        $clone->ticks = $newTicks;

        return $clone;
    }

    /**
     * Return clock seconds.
     *
     * @return int
     */
    public function getSeconds(): int
    {
        $secondsTicks = $this->ticks % self::TICKS_PER_MINUTE - $this->ticks % self::TICKS_PER_SECOND;

        return intdiv($secondsTicks, self::TICKS_PER_SECOND);
    }

    /**
     * Set clock seconds.
     *
     * @param int $seconds Clock seconds
     * @return static
     */
    public function setSeconds(int $seconds): static
    {
        $baseTicks = $this->ticks - ($this->ticks % self::TICKS_PER_MINUTE - $this->ticks % self::TICKS_PER_SECOND);
        $newTicks = static::mod($baseTicks + $seconds * self::TICKS_PER_SECOND, self::TICKS_PER_DAY);

        $clone = clone $this;
        $clone->ticks = $newTicks;

        return $clone;
    }

    /**
     * Returns clock minutes.
     *
     * @return int
     */
    public function getMinutes(): int
    {
        $minutesTicks = $this->ticks % self::TICKS_PER_HOUR - $this->ticks % self::TICKS_PER_MINUTE;

        return intdiv($minutesTicks, self::TICKS_PER_MINUTE);
    }

    /**
     * Set clock minutes.
     *
     * @param int $minutes Clock minutes
     * @return static
     */
    public function setMinutes(int $minutes): static
    {
        $baseTicks = $this->ticks - ($this->ticks % self::TICKS_PER_HOUR - $this->ticks % self::TICKS_PER_MINUTE);
        $newTicks = static::mod($baseTicks + $minutes * self::TICKS_PER_MINUTE, self::TICKS_PER_DAY);

        $clone = clone $this;
        $clone->ticks = $newTicks;

        return $clone;
    }

    /**
     * Returns clock hours.
     *
     * @return int
     */
    public function getHours(): int
    {
        $hoursInTicks = $this->ticks - $this->ticks % self::TICKS_PER_HOUR;

        return intdiv($hoursInTicks, self::TICKS_PER_HOUR);
    }

    /**
     * Set clock hours.
     *
     * @param int $hours Clock hours
     * @return static
     */
    public function setHours(int $hours): static
    {
        $baseTicks = $this->ticks - ($this->ticks - $this->ticks % self::TICKS_PER_HOUR);
        $newTicks = static::mod($baseTicks + $hours * self::TICKS_PER_HOUR, self::TICKS_PER_DAY);

        $clone = clone $this;
        $clone->ticks = $newTicks;

        return $clone;
    }

    /**
     * Sets clock time.
     *
     * @param int $hours Clock hours
     * @param int $minutes Clock minutes
     * @param int $seconds Clock seconds
     * @param int $microseconds Clock microseconds
     * @return static
     */
    public function setTime(int $hours = 0, int $minutes = 0, int $seconds = 0, int $microseconds = 0): static
    {
        $ticks = $hours * self::TICKS_PER_HOUR +
            $minutes * self::TICKS_PER_MINUTE +
            $seconds * self::TICKS_PER_SECOND +
            $microseconds * self::TICKS_PER_MICROSECOND;
        $ticks = static::mod($ticks, self::TICKS_PER_DAY);

        $clone = clone $this;
        $clone->ticks = $ticks;

        return $clone;
    }

    /**
     * @param int $a Left side
     * @param int $a Right side
     * @return int
     */
    protected static function mod(int $a, int $b): int
    {
        if ($a < 0) {
            return $a % $b + $b;
        }

        return $a % $b;
    }

    /**
     * Formats string using the same syntax as `DateTimeImmutable::format()`.
     *
     * As this uses DateTimeImmutable::format() to format the string, non-time formatters
     * will still be interpreted. Be sure to escape those characters first.
     *
     * @param string $format Format string
     * @return string
     */
    public function format(string $format): string
    {
        return $this->toNative()->format($format);
    }

    /**
     * Returns whether time is equal to target time.
     *
     * @param \Cake\Chronos\ChronosTime $target Target time
     * @return bool
     */
    public function equals(ChronosTime $target): bool
    {
        return $this->ticks === $target->ticks;
    }

    /**
     * Returns whether time is greater than target time.
     *
     * @param \Cake\Chronos\ChronosTime $target Target time
     * @return bool
     */
    public function greaterThan(ChronosTime $target): bool
    {
        return $this->ticks > $target->ticks;
    }

    /**
     * Returns whether time is greater than or equal to target time.
     *
     * @param \Cake\Chronos\ChronosTime $target Target time
     * @return bool
     */
    public function greaterThanOrEquals(ChronosTime $target): bool
    {
        return $this->ticks >= $target->ticks;
    }

    /**
     * Returns whether time is less than target time.
     *
     * @param \Cake\Chronos\ChronosTime $target Target time
     * @return bool
     */
    public function lessThan(ChronosTime $target): bool
    {
        return $this->ticks < $target->ticks;
    }

    /**
     * Returns whether time is less than or equal to target time.
     *
     * @param \Cake\Chronos\ChronosTime $target Target time
     * @return bool
     */
    public function lessThanOrEquals(ChronosTime $target): bool
    {
        return $this->ticks <= $target->ticks;
    }

    /**
     * Returns whether time is between time range.
     *
     * @param \Cake\Chronos\ChronosTime $start Start of target range
     * @param \Cake\Chronos\ChronosTime $end End of target range
     * @param bool $equals Whether to include the beginning and end of range
     * @return bool
     */
    public function between(ChronosTime $start, ChronosTime $end, bool $equals = true): bool
    {
        if ($start->greaterThan($end)) {
            [$start, $end] = [$end, $start];
        }

        if ($equals) {
            return $this->greaterThanOrEquals($start) && $this->lessThanOrEquals($end);
        }

        return $this->greaterThan($start) && $this->lessThan($end);
    }

    /**
     * Returns an `DateTimeImmutable` instance set to this clock time.
     *
     * @return \DateTimeImmutable
     */
    public function toNative(): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTime(
            $this->getHours(),
            $this->getMinutes(),
            $this->getSeconds(),
            $this->getMicroseconds()
        );
    }
}
