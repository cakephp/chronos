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

/**
 * Provides various comparison operator methods for datetime objects.
 */
trait ComparisonTrait
{
    /**
     * Days of weekend
     *
     * @var array
     */
    protected static array $weekendDays = [ChronosInterface::SATURDAY, ChronosInterface::SUNDAY];

    /**
     * Get weekend days
     *
     * @return array
     */
    public static function getWeekendDays(): array
    {
        return static::$weekendDays;
    }

    /**
     * Set weekend days
     *
     * @param array $days Which days are 'weekends'.
     * @return void
     */
    public static function setWeekendDays(array $days): void
    {
        static::$weekendDays = $days;
    }

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function equals(ChronosInterface $dateTime): bool
    {
        return $this == $dateTime;
    }

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function notEquals(ChronosInterface $dateTime): bool
    {
        return !$this->equals($dateTime);
    }

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function greaterThan(ChronosInterface $dateTime): bool
    {
        return $this > $dateTime;
    }

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function greaterThanOrEquals(ChronosInterface $dateTime): bool
    {
        return $this >= $dateTime;
    }

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function lessThan(ChronosInterface $dateTime): bool
    {
        return $this < $dateTime;
    }

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to compare with.
     * @return bool
     */
    public function lessThanOrEquals(ChronosInterface $dateTime): bool
    {
        return $this <= $dateTime;
    }

    /**
     * Determines if the instance is between two others
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dateTime2 The instance to compare with.
     * @param bool $equal Indicates if a > and < comparison should be used or <= or >=
     * @return bool
     */
    public function between(ChronosInterface $dateTime1, ChronosInterface $dateTime2, bool $equal = true): bool
    {
        if ($dateTime1->greaterThan($dateTime2)) {
            $temp = $dateTime1;
            $dateTime1 = $dateTime2;
            $dateTime2 = $temp;
        }

        if ($equal) {
            return $this->greaterThanOrEquals($dateTime1) && $this->lessThanOrEquals($dateTime2);
        }

        return $this->greaterThan($dateTime1) && $this->lessThan($dateTime2);
    }

    /**
     * Get the closest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dateTime2 The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function closest(ChronosInterface $dateTime1, ChronosInterface $dateTime2): ChronosInterface
    {
        return $this->diffInSeconds($dateTime1) < $this->diffInSeconds($dateTime2) ? $dateTime1 : $dateTime2;
    }

    /**
     * Get the farthest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dateTime2 The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function farthest(ChronosInterface $dateTime1, ChronosInterface $dateTime2): ChronosInterface
    {
        return $this->diffInSeconds($dateTime1) > $this->diffInSeconds($dateTime2) ? $dateTime1 : $dateTime2;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dateTime The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function min(?ChronosInterface $dateTime = null): ChronosInterface
    {
        $dateTime = $dateTime ?? static::now($this->tz);

        return $this->lessThan($dateTime) ? $this : $dateTime;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dateTime The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function max(?ChronosInterface $dateTime = null): ChronosInterface
    {
        $dateTime = $dateTime ?? static::now($this->tz);

        return $this->greaterThan($dateTime) ? $this : $dateTime;
    }

    /**
     * Determines if the instance is a weekday
     *
     * @return bool
     */
    public function isWeekday(): bool
    {
        return !$this->isWeekend();
    }

    /**
     * Determines if the instance is a weekend day
     *
     * @return bool
     */
    public function isWeekend(): bool
    {
        return in_array($this->dayOfWeek, static::$weekendDays, true);
    }

    /**
     * Determines if the instance is yesterday
     *
     * @return bool
     */
    public function isYesterday(): bool
    {
        return $this->toDateString() === static::yesterday($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is today
     *
     * @return bool
     */
    public function isToday(): bool
    {
        return $this->toDateString() === static::now($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is tomorrow
     *
     * @return bool
     */
    public function isTomorrow(): bool
    {
        return $this->toDateString() === static::tomorrow($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is within the next week
     *
     * @return bool
     */
    public function isNextWeek(): bool
    {
        return $this->format('W o') === static::now($this->tz)->addWeeks(1)->format('W o');
    }

    /**
     * Determines if the instance is within the last week
     *
     * @return bool
     */
    public function isLastWeek(): bool
    {
        return $this->format('W o') === static::now($this->tz)->subWeeks(1)->format('W o');
    }

    /**
     * Determines if the instance is within the next month
     *
     * @return bool
     */
    public function isNextMonth(): bool
    {
        return $this->format('m Y') === static::now($this->tz)->addMonths(1)->format('m Y');
    }

    /**
     * Determines if the instance is within the last month
     *
     * @return bool
     */
    public function isLastMonth(): bool
    {
        return $this->format('m Y') === static::now($this->tz)->subMonths(1)->format('m Y');
    }

    /**
     * Determines if the instance is within the next year
     *
     * @return bool
     */
    public function isNextYear(): bool
    {
        return $this->year === static::now($this->tz)->addYears(1)->year;
    }

    /**
     * Determines if the instance is within the last year
     *
     * @return bool
     */
    public function isLastYear(): bool
    {
        return $this->year === static::now($this->tz)->subYears(1)->year;
    }

    /**
     * Determines if the instance is in the future, ie. greater (after) than now
     *
     * @return bool
     */
    public function isFuture(): bool
    {
        return $this->greaterThan(static::now($this->tz));
    }

    /**
     * Determines if the instance is in the past, ie. less (before) than now
     *
     * @return bool
     */
    public function isPast(): bool
    {
        return $this->lessThan(static::now($this->tz));
    }

    /**
     * Determines if the instance is a leap year
     *
     * @return bool
     */
    public function isLeapYear(): bool
    {
        return $this->format('L') === '1';
    }

    /**
     * Checks if the passed in date is the same day as the instance current day.
     *
     * @param \Cake\Chronos\ChronosInterface $dateTime The instance to check against.
     * @return bool
     */
    public function isSameDay(ChronosInterface $dateTime): bool
    {
        return $this->toDateString() === $dateTime->toDateString();
    }

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::SUNDAY;
    }

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::MONDAY;
    }

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::TUESDAY;
    }

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::WEDNESDAY;
    }

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::THURSDAY;
    }

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::FRIDAY;
    }

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday(): bool
    {
        return $this->dayOfWeek === ChronosInterface::SATURDAY;
    }

    /**
     * Returns true if this object represents a date within the current week
     *
     * @return bool
     */
    public function isThisWeek(): bool
    {
        return static::now($this->getTimezone())->format('W o') === $this->format('W o');
    }

    /**
     * Returns true if this object represents a date within the current month
     *
     * @return bool
     */
    public function isThisMonth(): bool
    {
        return static::now($this->getTimezone())->format('m Y') === $this->format('m Y');
    }

    /**
     * Returns true if this object represents a date within the current year
     *
     * @return bool
     */
    public function isThisYear(): bool
    {
        return static::now($this->getTimezone())->format('Y') === $this->format('Y');
    }

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dateTime The instance to compare with or null to use current day.
     * @return bool
     */
    public function isBirthday(?ChronosInterface $dateTime = null): bool
    {
        $dateTime = $dateTime ?? static::now($this->tz);

        return $this->format('md') === $dateTime->format('md');
    }

    /**
     * Returns true this instance happened within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function wasWithinLast(string|int $timeInterval): bool
    {
        $now = new static();
        $interval = $now->modify('-' . $timeInterval);
        $thisTime = $this->format('U');

        return $thisTime >= $interval->format('U') && $thisTime <= $now->format('U');
    }

    /**
     * Returns true this instance will happen within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function isWithinNext(string|int $timeInterval): bool
    {
        $now = new static();
        $interval = $now->modify('+' . $timeInterval);
        $thisTime = $this->format('U');

        return $thisTime <= $interval->format('U') && $thisTime >= $now->format('U');
    }
}
