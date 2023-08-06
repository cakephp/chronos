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
namespace Cake\Chronos\Traits;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosInterface;
use DateTime;

/**
 * Provides various comparison operator methods for datetime objects.
 */
trait ComparisonTrait
{
    use CopyTrait;

    /**
     * Days of weekend
     *
     * @var array
     */
    protected static $weekendDays = [ChronosInterface::SATURDAY, ChronosInterface::SUNDAY];

    /**
     * Get weekend days
     *
     * @return array
     */
    public static function getWeekendDays(): array
    {
        if (static::class === ChronosDate::class) {
            trigger_error('2.5 getWeekendDays() will be removed in 3.x.', E_USER_DEPRECATED);
        }

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
        if (static::class === ChronosDate::class) {
            trigger_error('2.5 setWeekendDays() will be removed in 3.x.', E_USER_DEPRECATED);
        }

        static::$weekendDays = $days;
    }

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 eq() is deprecated. Use equals() instead.
     */
    public function eq(ChronosInterface $dt): bool
    {
        trigger_error('2.5 eq() is deprecated. Use equals() instead.', E_USER_DEPRECATED);

        return $this->equals($dt);
    }

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function equals(ChronosInterface $dt)
    {
        return $this == $dt;
    }

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 ne() is deprecated. Use notEquals() instead.
     */
    public function ne(ChronosInterface $dt): bool
    {
        trigger_error('2.5 ne() is deprecated. Use notEquals() instead.', E_USER_DEPRECATED);

        return $this->notEquals($dt);
    }

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function notEquals(ChronosInterface $dt)
    {
        return !$this->equals($dt);
    }

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 gt() is deprecated. Use greaterThan() instead.
     */
    public function gt(ChronosInterface $dt): bool
    {
        trigger_error('2.5 gt() is deprecated. Use greaterThan() instead.', E_USER_DEPRECATED);

        return $this->greaterThan($dt);
    }

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function greaterThan(ChronosInterface $dt)
    {
        return $this > $dt;
    }

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 gte() is deprecated. Use greaterThanOrEquals() instead.
     */
    public function gte(ChronosInterface $dt): bool
    {
        trigger_error('2.5 gte() is deprecated. Use greaterThanOrEquals() instead.', E_USER_DEPRECATED);

        return $this->greaterThanOrEquals($dt);
    }

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function greaterThanOrEquals(ChronosInterface $dt)
    {
        return $this >= $dt;
    }

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 lt() is deprecated. Use lessThan instead.
     */
    public function lt(ChronosInterface $dt): bool
    {
        trigger_error('2.5 lt() is deprecated. Use lessThan() instead.', E_USER_DEPRECATED);

        return $this->lessThan($dt);
    }

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lessThan(ChronosInterface $dt)
    {
        return $this < $dt;
    }

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     * @deprecated 2.5 lte() is deprecated. Use lessThanOrEquals() instead.
     */
    public function lte(ChronosInterface $dt): bool
    {
        trigger_error('2.5 lte() is deprecated. Use lessthanOrEquals() instead.', E_USER_DEPRECATED);

        return $this->lessThanOrEquals($dt);
    }

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lessThanOrEquals(ChronosInterface $dt)
    {
        return $this <= $dt;
    }

    /**
     * Determines if the instance is between two others
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @param bool $equal Indicates if a > and < comparison should be used or <= or >=
     * @return bool
     */
    public function between(ChronosInterface $dt1, ChronosInterface $dt2, bool $equal = true): bool
    {
        if ($dt1->greaterThan($dt2)) {
            $temp = $dt1;
            $dt1 = $dt2;
            $dt2 = $temp;
        }
        Chronos::checkTypes($dt1, $dt2);

        if ($equal) {
            return $this->greaterThanOrEquals($dt1) && $this->lessThanOrEquals($dt2);
        }

        return $this->greaterThan($dt1) && $this->lessThan($dt2);
    }

    /**
     * Get the closest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function closest(ChronosInterface $dt1, ChronosInterface $dt2): ChronosInterface
    {
        return $this->diffInSeconds($dt1) < $this->diffInSeconds($dt2) ? $dt1 : $dt2;
    }

    /**
     * Get the farthest date from the instance.
     *
     * @param \Cake\Chronos\ChronosInterface $dt1 The instance to compare with.
     * @param \Cake\Chronos\ChronosInterface $dt2 The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function farthest(ChronosInterface $dt1, ChronosInterface $dt2): ChronosInterface
    {
        return $this->diffInSeconds($dt1) > $this->diffInSeconds($dt2) ? $dt1 : $dt2;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function min(?ChronosInterface $dt = null): ChronosInterface
    {
        $dt = $dt ?? static::now($this->tz);

        return $this->lessThan($dt) ? $this : $dt;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with.
     * @return \Cake\Chronos\ChronosInterface
     */
    public function max(?ChronosInterface $dt = null): ChronosInterface
    {
        $dt = $dt ?? static::now($this->tz);

        return $this->greaterThan($dt) ? $this : $dt;
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
        return in_array($this->dayOfWeek, self::$weekendDays, true);
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
     * @param \Cake\Chronos\ChronosInterface $dt The instance to check against.
     * @return bool
     */
    public function isSameDay(ChronosInterface $dt): bool
    {
        return $this->toDateString() === $dt->toDateString();
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
     * @param \Cake\Chronos\ChronosInterface|null $dt The instance to compare with or null to use current day.
     * @return bool
     */
    public function isBirthday(?ChronosInterface $dt = null): bool
    {
        $dt = $dt ?? static::now($this->tz);

        return $this->format('md') === $dt->format('md');
    }

    /**
     * Returns true this instance happened within the specified interval
     *
     * @param string|int $timeInterval the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function wasWithinLast($timeInterval): bool
    {
        $now = new static();
        $interval = $now->copy()->modify('-' . $timeInterval);
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
    public function isWithinNext($timeInterval): bool
    {
        $now = new static();
        $interval = $now->copy()->modify('+' . $timeInterval);
        $thisTime = $this->format('U');

        return $thisTime <= $interval->format('U') && $thisTime >= $now->format('U');
    }

    /**
     * Check if instance of ChronosInterface is mutable.
     *
     * @return bool
     */
    public function isMutable(): bool
    {
        trigger_error('2.5 isMutable will be removed in the future', E_USER_DEPRECATED);

        return $this instanceof DateTime;
    }
}
