<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Traits;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * A trait for freezing the time aspect of a DateTime.
 *
 * Used in making calendar date objects, both mutable and immutable.
 */
trait FrozenTimeTrait
{
    use RelativeKeywordTrait;

    /**
     * Removes the time components from an input string.
     *
     * Used to ensure constructed objects always lack time.
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosDate|\DateTimeInterface|string $time The input time
     * @return string The date component of $time.
     */
    protected function stripTime(Chronos|ChronosDate|DateTimeInterface|string $time): string
    {
        if (is_string($time)) {
            $time = new DateTimeImmutable($time);
        }

        return $time->format('Y-m-d 00:00:00');
    }

    /**
     * Remove time components from strtotime relative strings.
     *
     * @param string $time The input expression
     * @return string The output expression with no time modifiers.
     */
    protected function stripRelativeTime(string $time): string
    {
        return preg_replace('/([-+]\s*\d+\s(?:minutes|seconds|hours|microseconds))/', '', $time);
    }

    /**
     * Modify the time on the Date.
     *
     * This method ignores all inputs and forces all inputs to 0.
     *
     * @param int $hours The hours to set (ignored)
     * @param int $minutes The minutes to set (ignored)
     * @param int $seconds The seconds to set (ignored)
     * @param int $microseconds The microseconds to set (ignored)
     * @return static A modified Date instance.
     */
    protected function setTime(int $hours, int $minutes, int $seconds = 0, int $microseconds = 0): static
    {
        $new = clone $this;
        $new->native = $new->native->setTime(0, 0, 0, 0);

        return $new;
    }

    /**
     * Add an Interval to a Date
     *
     * Any changes to the time will be ignored and reset to 00:00:00
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    public function add(DateInterval $interval): static
    {
        $new = clone $this;
        $new->native = $new->native->add($interval)->setTime(0, 0, 0);

        return $new;
    }

    /**
     * Subtract an Interval from a Date.
     *
     * Any changes to the time will be ignored and reset to 00:00:00
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    public function sub(DateInterval $interval): static
    {
        $new = clone $this;
        $new->native = $new->native->sub($interval)->setTime(0, 0, 0);

        return $new;
    }

    /**
     * Set the timestamp value and get a new object back.
     *
     * This method will discard the time aspects of the timestamp
     * and only apply the date portions
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function setTimestamp(int $value): static
    {
        $new = clone $this;
        $new->native = $new->native->setTimestamp($value)->setTime(0, 0, 0);

        return $new;
    }

    /**
     * Creates a new instance with date modified according to DateTimeImmutable::modifier().
     *
     * Changing any aspect of the time will be ignored, and the resulting object
     * will have its time frozen to 00:00:00.
     *
     * @param string $modifier Date modifier
     * @return static
     */
    public function modify(string $modifier): static
    {
        if (preg_match('/hour|minute|second/', $modifier)) {
            return clone $this;
        }

        $new = clone $this;
        $new->native = $new->native->modify($modifier);
        if ($new->native === false) {
            throw new InvalidArgumentException('Unable to modify date using: ' . $modifier);
        }

        if ($new->format('H:i:s') !== '00:00:00') {
            $new->native = $new->native->setTime(0, 0, 0);
        }

        return $new;
    }
}
