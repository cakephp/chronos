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
use InvalidArgumentException;

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
     * Constructs instance with time string in the format HH[:.]mm or HH[:.]mm[:.]ss.u
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosTime|\DateTimeInterface|string|null $time Time
     */
    public function __construct(Chronos|ChronosTime|DateTimeInterface|string|null $time = null)
    {
        if ($time === null) {
            $this->ticks = 0;
        } elseif ($time instanceof ChronosTime) {
            $this->ticks = $time->ticks;
        } else {
            $this->ticks = static::parseTime(is_string($time) ? $time : $time->format('H:i:s.u'));
        }
    }

    /**
     * @param string $time Time string in the format HH[:.]mm or HH[:.]mm[:.]ss.u
     * @return int
     */
    protected static function parseTime(string $time): int
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

        if ($hours > 23 || $minutes > 59 || $seconds > 59 || $microseconds > 999_999) {
            throw new InvalidArgumentException('Time string contains invalid values.');
        }

        $ticks = $hours * self::TICKS_PER_HOUR;
        $ticks += $minutes * self::TICKS_PER_MINUTE;
        $ticks += $seconds * self::TICKS_PER_SECOND;
        $ticks += $microseconds * self::TICKS_PER_MICROSECOND;

        return $ticks;
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
        $clone = clone $this;

        $prevTicks = $this->ticks % self::TICKS_PER_SECOND;
        $newTicks = static::mod($microseconds, 1_000_000) * self::TICKS_PER_MICROSECOND;

        $clone->ticks = $this->ticks - $prevTicks + $newTicks;

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
        $prevSecondsTicks = $this->ticks % self::TICKS_PER_MINUTE - $this->ticks % self::TICKS_PER_SECOND;
        $newSecondsTicks = static::mod($seconds, 60) * self::TICKS_PER_SECOND;

        $clone = clone $this;
        $clone->ticks = $this->ticks - $prevSecondsTicks + $newSecondsTicks;

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
        $prevMinutesTicks = $this->ticks % self::TICKS_PER_HOUR - $this->ticks % self::TICKS_PER_MINUTE;
        $newMinutesTicks = static::mod($minutes, 60) * self::TICKS_PER_MINUTE;

        $clone = clone $this;
        $clone->ticks = $this->ticks - $prevMinutesTicks + $newMinutesTicks;

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
        $prevHoursTicks = $this->ticks - $this->ticks % self::TICKS_PER_HOUR;
        $newHoursTicks = static::mod($hours, 24) * self::TICKS_PER_HOUR;

        $clone = clone $this;
        $clone->ticks = $this->ticks - $prevHoursTicks + $newHoursTicks;

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
        $clone = clone $this;
        $clone->ticks = static::mod($hours, 24) * self::TICKS_PER_HOUR +
            static::mod($minutes, 60) * self::TICKS_PER_MINUTE +
            static::mod($seconds, 60) * self::TICKS_PER_SECOND +
            static::mod($microseconds, 1_000_000) * self::TICKS_PER_MICROSECOND;

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
