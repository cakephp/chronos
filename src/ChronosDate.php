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

namespace Cake\Chronos;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * An immutable date object that converts all time components into 00:00:00.
 *
 * This class is useful when you want to represent a calendar date and ignore times.
 * This means that timezone changes take no effect as a calendar date exists in all timezones
 * in each respective date.
 *
 * @property-read int $year
 * @property-read int $yearIso
 * @property-read int $month
 * @property-read int $day
 * @property-read int $hour
 * @property-read int $minute
 * @property-read int $second
 * @property-read int $micro
 * @property-read int $microsecond
 * @property-read int $timestamp seconds since the Unix Epoch
 * @property-read \DateTimeZone $timezone the current timezone
 * @property-read \DateTimeZone $tz alias of timezone
 * @property-read int $dayOfWeek 1 (for Monday) through 7 (for Sunday)
 * @property-read int $dayOfYear 0 through 365
 * @property-read int $weekOfMonth 1 through 5
 * @property-read int $weekOfYear ISO-8601 week number of year, weeks starting on Monday
 * @property-read int $daysInMonth number of days in the given month
 * @property-read int $age does a diffInYears() with default parameters
 * @property-read int $quarter the quarter of this instance, 1 - 4
 * @property-read int $offset the timezone offset in seconds from UTC
 * @property-read int $offsetHours the timezone offset in hours from UTC
 * @property-read bool $dst daylight savings time indicator, true if DST, false otherwise
 * @property-read bool $local checks if the timezone is local, true if local, false otherwise
 * @property-read bool $utc checks if the timezone is UTC, true if UTC, false otherwise
 * @property-read string $timezoneName
 * @property-read string $tzName
 */
class ChronosDate extends DateTimeImmutable implements ChronosInterface
{
    use Traits\ComparisonTrait;
    use Traits\DifferenceTrait;
    use Traits\FactoryTrait;
    use Traits\FormattingTrait;
    use Traits\FrozenTimeTrait;
    use Traits\MagicPropertyTrait;
    use Traits\ModifierTrait;
    use Traits\TestingAidTrait;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = 'Y-m-d';

    /**
     * Create a new Immutable Date instance.
     *
     * You can specify the timezone for the $time parameter. This timezone will
     * not be used in any future modifications to the Date instance.
     *
     * The $timezone parameter is ignored if $time is a DateTimeInterface
     * instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * Date instances lack time components, however due to limitations in PHP's
     * internal Datetime object the time will always be set to 00:00:00, and the
     * timezone will always be the server local time. Normalizing the timezone allows for
     * subtraction/addition to have deterministic results.
     *
     * @param \DateTimeInterface|string|int|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $tz The timezone in which the date is taken
     */
    public function __construct($time = 'now', $tz = null)
    {
        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        $testNow = Chronos::getTestNow();
        if ($testNow === null || !static::isRelativeOnly($time)) {
            $time = $this->stripTime($time, $tz);
            parent::__construct($time);

            return;
        }

        $testNow = clone $testNow;
        if ($tz !== null && $tz !== $testNow->getTimezone()) {
            $testNow = $testNow->setTimezone($tz ?? date_default_timezone_get());
        }
        if (!empty($time)) {
            $testNow = $testNow->modify($time);
        }

        $time = $testNow->format('Y-m-d 00:00:00');
        parent::__construct($time);
    }

    /**
     * Create a new mutable instance from current immutable instance.
     *
     * @return \Cake\Chronos\MutableDate
     */
    public function toMutable(): MutableDate
    {
        trigger_error('2.5 Mutable classes will be removed in 3.0', E_USER_DEPRECATED);

        return MutableDate::instance($this);
    }

    /**
     * Return properties for debugging.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        $properties = [
            'hasFixedNow' => static::hasTestNow(),
            'date' => $this->format('Y-m-d'),
        ];

        return $properties;
    }

    /**
     * Create an instance from a specific date.
     *
     * @param ?int $year The year to create an instance with.
     * @param ?int $month The month to create an instance with.
     * @param ?int $day The day to create an instance with.
     * @return static
     */
    public static function create(?int $year = null, ?int $month = null, ?int $day = null)
    {
        $now = static::today();
        $year = $year ?? (int)$now->format('Y');
        $month = $month ?? $now->format('m');
        $day = $day ?? $now->format('d');

        $instance = static::createFromFormat(
            'Y-m-d',
            sprintf('%s-%s-%s', 0, $month, $day)
        );

        return $instance->addYears($year);
    }

    /**
     * Add an Interval to a Date
     *
     * Any changes to the time will cause an exception to be raised.
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    #[\ReturnTypeWillChange]
    public function add($interval)
    {
        if ($interval->f > 0 || $interval->s > 0 || $interval->i > 0 || $interval->h > 0) {
            trigger_error('2.5 Adding intervals with time components will be removed in 3.0', E_USER_DEPRECATED);
        }

        return parent::add($interval)->setTime(0, 0, 0);
    }

    /**
     * Subtract an Interval from a Date.
     *
     * Any changes to the time will cause an exception to be raised.
     *
     * @param \DateInterval $interval The interval to modify this date by.
     * @return static A modified Date instance
     */
    #[\ReturnTypeWillChange]
    public function sub($interval)
    {
        if ($interval->f > 0 || $interval->s > 0 || $interval->i > 0 || $interval->h > 0) {
            trigger_error('2.5 Subtracting intervals with time components will be removed in 3.0', E_USER_DEPRECATED);
        }

        return parent::sub($interval)->setTime(0, 0, 0);
    }

    /**
     * Creates a new instance with date modified according to DateTimeImmutable::modifier().
     *
     * Attempting to change a time component will raise an exception
     *
     * @param string $modifier Date modifier
     * @return static
     */
    #[\ReturnTypeWillChange]
    public function modify($modifier)
    {
        if (preg_match('/hour|minute|second/', $modifier)) {
            trigger_error('2.5 Modifying dates with time values will be removed in 3.0', E_USER_DEPRECATED);
        }

        $new = parent::modify($modifier);
        if ($new === false) {
            throw new InvalidArgumentException('Unable to modify date using: ' . $modifier);
        }

        if ($new->format('H:i:s') !== '00:00:00') {
            $new = $new->setTime(0, 0, 0);
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function setTimestamp($value)
    {
        trigger_error('2.5 Setting timestamp values on Date values will be removed in 3.0', E_USER_DEPRECATED);

        return parent::setTimestamp($value);
    }

    /**
     * @inheritDoc
     */
    public function hour(int $value): ChronosInterface
    {
        trigger_error('2.5 Modifying hours on Date values will be removed in 3.0', E_USER_DEPRECATED);

        return $this->setTime($value, $this->minute, $this->second);
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute(int $value): ChronosInterface
    {
        trigger_error('2.5 Modifying minutes on Date values will be removed in 3.0', E_USER_DEPRECATED);

        return $this->setTime($this->hour, $value, $this->second);
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second(int $value): ChronosInterface
    {
        trigger_error('2.5 Modifying second on Date values will be removed in 3.0', E_USER_DEPRECATED);

        return $this->setTime($this->hour, $this->minute, $value);
    }

    /**
     * Set the instance's microsecond
     *
     * @param int $value The microsecond value.
     * @return static
     */
    public function microsecond(int $value): ChronosInterface
    {
        trigger_error('2.5 Modifying microsecond on Date values will be removed in 3.0', E_USER_DEPRECATED);

        return $this->setTime($this->hour, $this->minute, $this->second, $value);
    }
}

class_alias('Cake\Chronos\ChronosDate', 'Cake\Chronos\Date');
