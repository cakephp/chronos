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
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * An Immutable extension on the native DateTime object.
 *
 * Adds a number of convenience APIs methods and the ability
 * to easily convert into a mutable object.
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
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
class Chronos
{
    use Traits\FormattingTrait;
    use Traits\RelativeKeywordTrait;

    /**
     * @var int
     */
    public const MONDAY = 1;

    /**
     * @var int
     */
    public const TUESDAY = 2;

    /**
     * @var int
     */
    public const WEDNESDAY = 3;

    /**
     * @var int
     */
    public const THURSDAY = 4;

    /**
     * @var int
     */
    public const FRIDAY = 5;

    /**
     * @var int
     */
    public const SATURDAY = 6;

    /**
     * @var int
     */
    public const SUNDAY = 7;

    /**
     * @var int
     */
    public const YEARS_PER_CENTURY = 100;

    /**
     * @var int
     */
    public const YEARS_PER_DECADE = 10;

    /**
     * @var int
     */
    public const MONTHS_PER_YEAR = 12;

    /**
     * @var int
     */
    public const MONTHS_PER_QUARTER = 3;

    /**
     * @var int
     */
    public const WEEKS_PER_YEAR = 52;

    /**
     * @var int
     */
    public const DAYS_PER_WEEK = 7;

    /**
     * @var int
     */
    public const HOURS_PER_DAY = 24;

    /**
     * @var int
     */
    public const MINUTES_PER_HOUR = 60;

    /**
     * @var int
     */
    public const SECONDS_PER_MINUTE = 60;

    /**
     * Default format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    public const DEFAULT_TO_STRING_FORMAT = 'Y-m-d H:i:s';

    /**
     * A test Chronos instance to be returned when now instances are created
     *
     * There is a single test now for all date/time classes provided by Chronos.
     * This aims to emulate stubbing out 'now' which is a single global fact.
     *
     * @var \Cake\Chronos\Chronos|null
     */
    protected static ?Chronos $testNow = null;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static string $toStringFormat = self::DEFAULT_TO_STRING_FORMAT;

    /**
     * Days of weekend
     *
     * @var array
     */
    protected static array $weekendDays = [Chronos::SATURDAY, Chronos::SUNDAY];

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static array $days = [
        Chronos::MONDAY => 'Monday',
        Chronos::TUESDAY => 'Tuesday',
        Chronos::WEDNESDAY => 'Wednesday',
        Chronos::THURSDAY => 'Thursday',
        Chronos::FRIDAY => 'Friday',
        Chronos::SATURDAY => 'Saturday',
        Chronos::SUNDAY => 'Sunday',
    ];

    /**
     * First day of week
     *
     * @var int
     */
    protected static int $weekStartsAt = Chronos::MONDAY;

    /**
     * Last day of week
     *
     * @var int
     */
    protected static int $weekEndsAt = Chronos::SUNDAY;

    /**
     * Instance of the diff formatting object.
     *
     * @var \Cake\Chronos\DifferenceFormatterInterface|null
     */
    protected static ?DifferenceFormatterInterface $diffFormatter = null;

    /**
     * @var \DateTimeImmutable
     */
    protected DateTimeImmutable $native;

    /**
     * Create a new Chronos instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosDate|\DateTimeInterface|string|int|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $timezone The timezone for the instance
     */
    public function __construct(
        Chronos|ChronosDate|DateTimeInterface|string|int|null $time = 'now',
        DateTimeZone|string|null $timezone = null
    ) {
        $this->native = $this->createNative($time, $timezone);
    }

    /**
     * Initializes the PHP DateTimeImmutable object.
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosDate|\DateTimeInterface|string|int|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $timezone The timezone for the instance
     * @return \DateTimeImmutable
     */
    protected function createNative(
        Chronos|ChronosDate|DateTimeInterface|string|int|null $time,
        DateTimeZone|string|null $timezone = null
    ): DateTimeImmutable {
        if (is_int($time) || (is_string($time) && ctype_digit($time))) {
            return new DateTimeImmutable("@{$time}");
        }

        if ($timezone !== null) {
            $timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
        }

        if (is_object($time)) {
            if (!$time instanceof ChronosDate) {
                $timezone = $time->getTimezone();
            }
            $time = $time->format('Y-m-d H:i:s.u');
        }

        $testNow = static::getTestNow();
        if ($testNow === null) {
            return new DateTimeImmutable($time ?? 'now', $timezone);
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            return new DateTimeImmutable($time, $timezone);
        }

        $testNow = clone $testNow;
        $relativetime = self::isTimeExpression($time);
        if (!$relativetime && $timezone !== $testNow->getTimezone()) {
            $testNow = $testNow->setTimezone($timezone ?? date_default_timezone_get());
        }

        if ($relative) {
            $testNow = $testNow->modify($time);
        }

        return new DateTimeImmutable($testNow->format('Y-m-d H:i:s.u'), $timezone);
    }

    /**
     * Set a Chronos instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. Chronos::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Chronos(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Chronos('now')
     *   - When a string containing the desired time is passed to Chronos::parse()
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * @param \Cake\Chronos\Chronos|string|null $testNow The instance to use for all future instances.
     * @return void
     */
    public static function setTestNow(Chronos|string|null $testNow = null): void
    {
        static::$testNow = is_string($testNow) ? static::parse($testNow) : $testNow;
    }

    /**
     * Get the Chronos instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return \Cake\Chronos\Chronos|null The current instance used for testing
     */
    public static function getTestNow(): ?Chronos
    {
        return static::$testNow;
    }

    /**
     * Determine if there is a valid test instance set. A valid test instance
     * is anything that is not null.
     *
     * @return bool True if there is a test instance, otherwise false
     */
    public static function hasTestNow(): bool
    {
        return static::$testNow !== null;
    }

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
     * Get the first day of week
     *
     * @return int
     */
    public static function getWeekStartsAt(): int
    {
        return static::$weekStartsAt;
    }

    /**
     * Set the first day of week
     *
     * @param int $day The day the week starts with.
     * @return void
     */
    public static function setWeekStartsAt(int $day): void
    {
        static::$weekStartsAt = $day;
    }

    /**
     * Get the last day of week
     *
     * @return int
     */
    public static function getWeekEndsAt(): int
    {
        return static::$weekEndsAt;
    }

    /**
     * Set the last day of week
     *
     * @param int $day The day the week ends with.
     * @return void
     */
    public static function setWeekEndsAt(int $day): void
    {
        static::$weekEndsAt = $day;
    }

    /**
     * Get the difference formatter instance or overwrite the current one.
     *
     * @param \Cake\Chronos\DifferenceFormatterInterface|null $formatter The formatter instance when setting.
     * @return \Cake\Chronos\DifferenceFormatterInterface The formatter instance.
     */
    public static function diffFormatter(?DifferenceFormatterInterface $formatter = null): DifferenceFormatterInterface
    {
        if ($formatter === null) {
            if (static::$diffFormatter === null) {
                static::$diffFormatter = new DifferenceFormatter();
            }

            return static::$diffFormatter;
        }

        return static::$diffFormatter = $formatter;
    }

    /**
     * Create an instance from a DateTimeInterface
     *
     * @param \DateTimeInterface $other The datetime instance to convert.
     * @return static
     */
    public static function instance(DateTimeInterface $other): static
    {
        return new static($other->format('Y-m-d H:i:s.u'), $other->getTimezone());
    }

    /**
     * Create an instance from a string.  This is an alias for the
     * constructor that allows better fluent syntax as it allows you to do
     * Chronos::parse('Monday next week')->fn() rather than
     * (new Chronos('Monday next week'))->fn()
     *
     * @param \Cake\Chronos\Chronos|\Cake\Chronos\ChronosDate|\DateTimeInterface|string|int|null $time The strtotime compatible string to parse
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name.
     * @return static
     */
    public static function parse(
        Chronos|ChronosDate|DateTimeInterface|string|int|null $time = 'now',
        DateTimeZone|string|null $timezone = null
    ): static {
        return new static($time, $timezone);
    }

    /**
     * Get an instance for the current date and time
     *
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name.
     * @return static
     */
    public static function now(DateTimeZone|string|null $timezone = null): static
    {
        return new static('now', $timezone);
    }

    /**
     * Create an instance for today
     *
     * @param \DateTimeZone|string|null $timezone The timezone to use.
     * @return static
     */
    public static function today(DateTimeZone|string|null $timezone = null): static
    {
        return new static('midnight', $timezone);
    }

    /**
     * Create an instance for tomorrow
     *
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function tomorrow(DateTimeZone|string|null $timezone = null): static
    {
        return new static('tomorrow, midnight', $timezone);
    }

    /**
     * Create an instance for yesterday
     *
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function yesterday(DateTimeZone|string|null $timezone = null): static
    {
        return new static('yesterday, midnight', $timezone);
    }

    /**
     * Create an instance for the greatest supported date.
     *
     * @return static
     */
    public static function maxValue(): static
    {
        return static::createFromTimestampUTC(PHP_INT_MAX);
    }

    /**
     * Create an instance for the lowest supported date.
     *
     * @return static
     */
    public static function minValue(): static
    {
        $max = PHP_INT_SIZE === 4 ? PHP_INT_MAX : PHP_INT_MAX / 10;

        return static::createFromTimestampUTC(~$max);
    }

    /**
     * Create an instance from a specific date and time.
     *
     * If any of $year, $month or $day are set to null their now() values
     * will be used.
     *
     * If $hour is null it will be set to its now() value and the default values
     * for $minute, $second and $microsecond will be their now() values.
     * If $hour is not null then the default values for $minute, $second
     * and $microsecond will be 0.
     *
     * @param int|null $year The year to create an instance with.
     * @param int|null $month The month to create an instance with.
     * @param int|null $day The day to create an instance with.
     * @param int|null $hour The hour to create an instance with.
     * @param int|null $minute The minute to create an instance with.
     * @param int|null $second The second to create an instance with.
     * @param int|null $microsecond The microsecond to create an instance with.
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function create(
        ?int $year = null,
        ?int $month = null,
        ?int $day = null,
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?int $microsecond = null,
        DateTimeZone|string|null $timezone = null
    ): static {
        $now = static::now();
        $year = $year ?? (int)$now->format('Y');
        $month = $month ?? $now->format('m');
        $day = $day ?? $now->format('d');

        if ($hour === null) {
            $hour = $now->format('H');
            $minute = $minute ?? $now->format('i');
            $second = $second ?? $now->format('s');
            $microsecond = $microsecond ?? $now->format('u');
        } else {
            $minute = $minute ?? 0;
            $second = $second ?? 0;
            $microsecond = $microsecond ?? 0;
        }

        $instance = static::createFromFormat(
            'Y-m-d H:i:s.u',
            sprintf('%s-%s-%s %s:%02s:%02s.%06s', 0, $month, $day, $hour, $minute, $second, $microsecond),
            $timezone
        );

        return $instance->addYears($year);
    }

    /**
     * Create an instance from just a date. The time portion is set to now.
     *
     * @param int|null $year The year to create an instance with.
     * @param int|null $month The month to create an instance with.
     * @param int|null $day The day to create an instance with.
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromDate(
        ?int $year = null,
        ?int $month = null,
        ?int $day = null,
        DateTimeZone|string|null $timezone = null
    ): static {
        return static::create($year, $month, $day, null, null, null, null, $timezone);
    }

    /**
     * Create an instance from just a time. The date portion is set to today.
     *
     * @param int|null $hour The hour to create an instance with.
     * @param int|null $minute The minute to create an instance with.
     * @param int|null $second The second to create an instance with.
     * @param int|null $microsecond The microsecond to create an instance with.
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromTime(
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?int $microsecond = null,
        DateTimeZone|string|null $timezone = null
    ): static {
        return static::create(null, null, null, $hour, $minute, $second, $microsecond, $timezone);
    }

    /**
     * Create an instance from a specific format
     *
     * @param string $format The date() compatible format string.
     * @param string $time The formatted date string to interpret.
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function createFromFormat(
        string $format,
        string $time,
        DateTimeZone|string|null $timezone = null
    ): static {
        if ($timezone !== null) {
            $dateTime = DateTimeImmutable::createFromFormat($format, $time, static::safeCreateDateTimeZone($timezone));
        } else {
            $dateTime = DateTimeImmutable::createFromFormat($format, $time);
        }

        $errors = DateTimeImmutable::getLastErrors();
        if (!$dateTime) {
            throw new InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
        }

        $dateTime = new static($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());

        return $dateTime;
    }

    /**
     * Creates an instance from an array of date and time values.
     *
     * The 'year', 'month' and 'day' values must all be set for a date. The time
     * values all default to 0.
     *
     * The 'timezone' value can be any format supported by `\DateTimeZone`.
     *
     * Allowed values:
     *  - year
     *  - month
     *  - day
     *  - hour
     *  - minute
     *  - second
     *  - microsecond
     *  - meridian ('am' or 'pm')
     *  - timezone
     *
     * @param array<int|string> $values Array of date and time values.
     * @return static
     */
    public static function createFromArray(array $values): static
    {
        $values += ['hour' => 0, 'minute' => 0, 'second' => 0, 'microsecond' => 0, 'timezone' => null];

        $formatted = '';
        if (
            isset($values['year'], $values['month'], $values['day']) &&
            (
                is_numeric($values['year']) &&
                is_numeric($values['month']) &&
                is_numeric($values['day'])
            )
        ) {
            $formatted .= sprintf('%04d-%02d-%02d ', $values['year'], $values['month'], $values['day']);
        }

        if (isset($values['meridian']) && (int)$values['hour'] === 12) {
            $values['hour'] = 0;
        }
        if (isset($values['meridian'])) {
            $values['hour'] = strtolower($values['meridian']) === 'am' ? $values['hour'] : $values['hour'] + 12;
        }
        $formatted .= sprintf(
            '%02d:%02d:%02d.%06d',
            $values['hour'],
            $values['minute'],
            $values['second'],
            $values['microsecond']
        );

        return static::parse($formatted, $values['timezone']);
    }

    /**
     * Create an instance from a timestamp
     *
     * @param int $timestamp The timestamp to create an instance from.
     * @param \DateTimeZone|string|null $timezone The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromTimestamp(int $timestamp, DateTimeZone|string|null $timezone = null): static
    {
        return static::now($timezone)->setTimestamp($timestamp);
    }

    /**
     * Create an instance from an UTC timestamp
     *
     * @param int $timestamp The UTC timestamp to create an instance from.
     * @return static
     */
    public static function createFromTimestampUTC(int $timestamp): static
    {
        return new static($timestamp);
    }

    /**
     * Creates a DateTimeZone from a string or a DateTimeZone
     *
     * @param \DateTimeZone|string|null $object The value to convert.
     * @return \DateTimeZone
     * @throws \InvalidArgumentException
     */
    protected static function safeCreateDateTimeZone(DateTimeZone|string|null $object): DateTimeZone
    {
        if ($object === null) {
            return new DateTimeZone(date_default_timezone_get());
        }

        if ($object instanceof DateTimeZone) {
            return $object;
        }

        return new DateTimeZone($object);
    }

    /**
     * Create a new DateInterval instance from specified values.
     *
     * @param int|null $years The year to use.
     * @param int|null $months The month to use.
     * @param int|null $weeks The week to use.
     * @param int|null $days The day to use.
     * @param int|null $hours The hours to use.
     * @param int|null $minutes The minutes to use.
     * @param int|null $seconds The seconds to use.
     * @param int|null $microseconds The microseconds to use.
     * @return \DateInterval
     */
    public static function createInterval(
        ?int $years = null,
        ?int $months = null,
        ?int $weeks = null,
        ?int $days = null,
        ?int $hours = null,
        ?int $minutes = null,
        ?int $seconds = null,
        ?int $microseconds = null,
    ): DateInterval {
        $spec = 'P';

        if ($years) {
            $spec .= $years . 'Y';
        }
        if ($months) {
            $spec .= $months . 'M';
        }
        if ($weeks) {
            $spec .= $weeks . 'W';
        }
        if ($days) {
            $spec .= $days . 'D';
        }

        if ($hours || $minutes || $seconds) {
            $spec .= 'T';
            if ($hours) {
                $spec .= $hours . 'H';
            }
            if ($minutes) {
                $spec .= $minutes . 'M';
            }
            if ($seconds) {
                $spec .= $seconds . 'S';
            }
        }

        if ($microseconds && $spec === 'P') {
            $spec .= 'T0S';
        }

        $instance = new DateInterval($spec);

        if ($microseconds) {
            $instance->f = $microseconds / 1000000;
        }

        return $instance;
    }

    /**
     * Sets the date and time.
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @param int $hour The hour to set.
     * @param int $minute The minute to set.
     * @param int $second The second to set.
     * @return static
     */
    public function setDateTime(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second = 0
    ): static {
        return $this->setDate($year, $month, $day)->setTime($hour, $minute, $second);
    }

    /**
     * Sets the date.
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @return static
     */
    public function setDate(int $year, int $month, int $day): static
    {
        $new = clone $this;
        $new->native = $new->native->setDate($year, $month, $day);

        return $new;
    }

    /**
     * Sets the time.
     *
     * @param int $hours Hours of the time
     * @param int $minutes Minutes of the time
     * @param int $seconds Seconds of the time
     * @param int $microseconds Microseconds of the time
     * @return static
     */
    public function setTime(int $hours, int $minutes, int $seconds = 0, int $microseconds = 0): static
    {
        $new = clone $this;
        $new->native = $new->native->setTime($hours, $minutes, $seconds, $microseconds);

        return $new;
    }

    /**
     * Creates a new instance with date modified according to DateTimeImmutable::modifier().
     *
     * @param string $modifier Date modifier
     * @return static
     * @throws \InvalidArgumentException
     * @see https://www.php.net/manual/en/datetimeimmutable.modify.php
     */
    public function modify(string $modifier): static
    {
        $native = $this->native->modify($modifier);
        if ($native === false) {
            throw new InvalidArgumentException('Unable to modify date using: ' . $modifier);
        }

        $new = clone $this;
        $new->native = $native;

        return $new;
    }

    /**
     * Returns the difference between this instance and target.
     *
     * @param \Cake\Chronos\Chronos|\DateTimeInterface $target Target instance
     * @param bool $absolute Whether the interval is forced to be positive
     * @return \DateInterval
     */
    public function diff(Chronos|DateTimeInterface $target, bool $absolute = false): DateInterval
    {
        $target = $target instanceof DateTimeInterface ? $target : $target->native;

        return $this->native->diff($target, $absolute);
    }

    /**
     * Returns formatted date string according to DateTimeImmutable::format().
     *
     * @param string $format String format
     * @return string
     */
    public function format(string $format): string
    {
        return $this->native->format($format);
    }

    /**
     * Returns the timezone offset.
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->native->getOffset();
    }

    /**
     * Sets the date and time based on a Unix timestamp.
     *
     * @param int $timestamp Unix timestamp representing the date
     * @return static
     */
    public function setTimestamp(int $timestamp): static
    {
        $new = clone $this;
        $new->native = $new->native->setTimestamp($timestamp);

        return $new;
    }

    /**
     * Gets the Unix timestamp for this instance.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->native->getTimestamp();
    }

    /**
     * Set the instance's timezone from a string or object
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function setTimezone(DateTimeZone|string $value): static
    {
        $new = clone $this;
        $new->native = $new->native->setTimezone(static::safeCreateDateTimeZone($value));

        return $new;
    }

    /**
     * Return time zone set for this instance.
     *
     * @return \DateTimeZone|false
     */
    public function getTimezone(): DateTimeZone|false
    {
        return $this->native->getTimezone();
    }

    /**
     * Set the time by time string
     *
     * @param string $time Time as string.
     * @return static
     */
    public function setTimeFromTimeString(string $time): static
    {
        $time = explode(':', $time);
        $hour = $time[0];
        $minute = $time[1] ?? 0;
        $second = $time[2] ?? 0;

        return $this->setTime((int)$hour, (int)$minute, (int)$second);
    }

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function timestamp(int $value): static
    {
        return $this->setTimestamp($value);
    }

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return static
     */
    public function year(int $value): static
    {
        return $this->setDate($value, $this->month, $this->day);
    }

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return static
     */
    public function month(int $value): static
    {
        return $this->setDate($this->year, $value, $this->day);
    }

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return static
     */
    public function day(int $value): static
    {
        return $this->setDate($this->year, $this->month, $value);
    }

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return static
     */
    public function hour(int $value): static
    {
        return $this->setTime($value, $this->minute, $this->second);
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute(int $value): static
    {
        return $this->setTime($this->hour, $value, $this->second);
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second(int $value): static
    {
        return $this->setTime($this->hour, $this->minute, $value);
    }

    /**
     * Set the instance's microsecond
     *
     * @param int $value The microsecond value.
     * @return static
     */
    public function microsecond(int $value): static
    {
        return $this->setTime($this->hour, $this->minute, $this->second, $value);
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * If the new ChronosDate does not exist, the last day of the month is used
     * instead instead of overflowing into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-01-03'))->addYears(1); // Results in 2016-01-03
     *
     *  (new Chronos('2012-02-29'))->addYears(1); // Results in 2013-02-28
     * ```
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYears(int $value): static
    {
        $month = $this->month;
        $date = $this->modify($value . ' years');

        if ($date->month !== $month) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Remove years from the instance.
     *
     * Has the same behavior as `addYears()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYears(int $value): static
    {
        return $this->addYears(-$value);
    }

    /**
     * Add years with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * If the new ChronosDate does not exist, the days overflow into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2012-02-29'))->addYearsWithOverflow(1); // Results in 2013-03-01
     * ```
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYearsWithOverflow(int $value): static
    {
        return $this->modify($value . ' year');
    }

    /**
     * Remove years with overflow from the instance
     *
     * Has the same behavior as `addYeasrWithOverflow()`.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYearsWithOverflow(int $value): static
    {
        return $this->addYearsWithOverflow(-1 * $value);
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2015-01-03'))->addMonths(1); // Results in 2015-02-03
     *
     *  (new Chronos('2015-01-31'))->addMonths(1); // Results in 2015-02-28
     * ```
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonths(int $value): static
    {
        $day = $this->day;
        $date = $this->modify($value . ' months');

        if ($date->day !== $day) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Remove months from the instance
     *
     * Has the same behavior as `addMonths()`.
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonths(int $value): static
    {
        return $this->addMonths(-$value);
    }

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * If the new ChronosDate does not exist, the days overflow into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2012-01-30'))->addMonthsWithOverflow(1); // Results in 2013-03-01
     * ```
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthsWithOverflow(int $value): static
    {
        return $this->modify($value . ' months');
    }

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * If the new ChronosDate does not exist, the days overflow into the next month.
     *
     * ### Example:
     *
     * ```
     *  (new Chronos('2012-01-30'))->addMonthsWithOverflow(1); // Results in 2013-03-01
     * ```
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthsWithOverflow(int $value): static
    {
        return $this->addMonthsWithOverflow(-1 * $value);
    }

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDays(int $value): static
    {
        return $this->modify("$value days");
    }

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDays(int $value): static
    {
        return $this->addDays(-$value);
    }

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekdays(int $value): static
    {
        return $this->modify($value . ' weekdays, ' . $this->format('H:i:s'));
    }

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekdays(int $value): static
    {
        return $this->addWeekdays(-$value);
    }

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeeks(int $value): static
    {
        return $this->modify("$value week");
    }

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeeks(int $value): static
    {
        return $this->addWeeks(-$value);
    }

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHours(int $value): static
    {
        return $this->modify("$value hour");
    }

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHours(int $value): static
    {
        return $this->addHours(-$value);
    }

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinutes(int $value): static
    {
        return $this->modify("$value minute");
    }

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinutes(int $value): static
    {
        return $this->addMinutes(-$value);
    }

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSeconds(int $value): static
    {
        return $this->modify("$value second");
    }

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSeconds(int $value): static
    {
        return $this->addSeconds(-$value);
    }

    /**
     * Resets the time to 00:00:00
     *
     * @return static
     */
    public function startOfDay(): static
    {
        return $this->modify('midnight');
    }

    /**
     * Resets the time to 23:59:59
     *
     * @return static
     */
    public function endOfDay(): static
    {
        return $this->modify('23:59:59');
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return static
     */
    public function startOfMonth(): static
    {
        return $this->modify('first day of this month midnight');
    }

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return static
     */
    public function endOfMonth(): static
    {
        return $this->modify('last day of this month, 23:59:59');
    }

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return static
     */
    public function startOfYear(): static
    {
        return $this->modify('first day of january midnight');
    }

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return static
     */
    public function endOfYear(): static
    {
        return $this->modify('last day of december, 23:59:59');
    }

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return static
     */
    public function startOfDecade(): static
    {
        $year = $this->year - $this->year % Chronos::YEARS_PER_DECADE;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return static
     */
    public function endOfDecade(): static
    {
        $year = $this->year - $this->year % Chronos::YEARS_PER_DECADE + Chronos::YEARS_PER_DECADE - 1;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return static
     */
    public function startOfCentury(): static
    {
        $year = $this->startOfYear()
            ->year($this->year - 1 - ($this->year - 1) % Chronos::YEARS_PER_CENTURY + 1)
            ->year;

        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return static
     */
    public function endOfCentury(): static
    {
        $y = $this->year - 1
            - ($this->year - 1)
            % Chronos::YEARS_PER_CENTURY
            + Chronos::YEARS_PER_CENTURY;

        $year = $this->endOfYear()
            ->year($y)
            ->year;

        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return static
     */
    public function startOfWeek(): static
    {
        $dateTime = $this;
        if ($dateTime->dayOfWeek !== static::$weekStartsAt) {
            $dateTime = $dateTime->previous(static::$weekStartsAt);
        }

        return $dateTime->startOfDay();
    }

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59
     *
     * @return static
     */
    public function endOfWeek(): static
    {
        $dateTime = $this;
        if ($dateTime->dayOfWeek !== static::$weekEndsAt) {
            $dateTime = $dateTime->next(static::$weekEndsAt);
        }

        return $dateTime->endOfDay();
    }

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function next(?int $dayOfWeek = null): mixed
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek;
        }

        $day = static::$days[$dayOfWeek];

        return $this->modify("next $day, midnight");
    }

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function previous(?int $dayOfWeek = null): mixed
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek;
        }

        $day = static::$days[$dayOfWeek];

        return $this->modify("last $day, midnight");
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfMonth(?int $dayOfWeek = null): mixed
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("first $day of this month, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfMonth(?int $dayOfWeek = null): mixed
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("last $day of this month, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfMonth(int $nth, int $dayOfWeek): mixed
    {
        $dateTime = $this->firstOfMonth();
        $check = $dateTime->format('Y-m');
        $dateTime = $dateTime->modify("+$nth " . static::$days[$dayOfWeek]);

        return $dateTime->format('Y-m') === $check ? $dateTime : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfQuarter(?int $dayOfWeek = null): mixed
    {
        return $this
            ->day(1)
            ->month($this->quarter * Chronos::MONTHS_PER_QUARTER - 2)
            ->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfQuarter(?int $dayOfWeek = null): mixed
    {
        return $this
            ->day(1)
            ->month($this->quarter * Chronos::MONTHS_PER_QUARTER)
            ->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfQuarter(int $nth, int $dayOfWeek): mixed
    {
        $dateTime = $this->day(1)->month($this->quarter * Chronos::MONTHS_PER_QUARTER);
        $lastMonth = $dateTime->month;
        $year = $dateTime->year;
        $dateTime = $dateTime->firstOfQuarter()->modify("+$nth" . static::$days[$dayOfWeek]);

        return $lastMonth < $dateTime->month || $year !== $dateTime->year ? false : $dateTime;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfYear(?int $dayOfWeek = null): mixed
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("first $day of january, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int|null $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfYear(?int $dayOfWeek = null): mixed
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];

        return $this->modify("last $day of december, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. Chronos::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfYear(int $nth, int $dayOfWeek): mixed
    {
        $dateTime = $this->firstOfYear()->modify("+$nth " . static::$days[$dayOfWeek]);

        return $this->year === $dateTime->year ? $dateTime : false;
    }

    /**
     * Determines if the instance is equal to another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function equals(Chronos $other): bool
    {
        return $this->native == $other->native;
    }

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function notEquals(Chronos $other): bool
    {
        return !$this->equals($other);
    }

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function greaterThan(Chronos $other): bool
    {
        return $this->native > $other->native;
    }

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function greaterThanOrEquals(Chronos $other): bool
    {
        return $this->native >= $other->native;
    }

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function lessThan(Chronos $other): bool
    {
        return $this->native < $other->native;
    }

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Cake\Chronos\Chronos $other The instance to compare with.
     * @return bool
     */
    public function lessThanOrEquals(Chronos $other): bool
    {
        return $this->native <= $other->native;
    }

    /**
     * Determines if the instance is between two others
     *
     * @param \Cake\Chronos\Chronos $first The instance to compare with.
     * @param \Cake\Chronos\Chronos $second The instance to compare with.
     * @param bool $equals If true, use >= and <= comparisons
     * @return bool
     */
    public function between(Chronos $first, Chronos $second, bool $equals = true): bool
    {
        if ($first->greaterThan($second)) {
            $temp = $first;
            $first = $second;
            $second = $temp;
        }

        if ($equals) {
            return $this->greaterThanOrEquals($first) && $this->lessThanOrEquals($second);
        }

        return $this->greaterThan($first) && $this->lessThan($second);
    }

    /**
     * Get the closest date from the instance.
     *
     * @param \Cake\Chronos\Chronos $first The instance to compare with.
     * @param \Cake\Chronos\Chronos $second The instance to compare with.
     * @return self
     */
    public function closest(Chronos $first, Chronos $second): Chronos
    {
        return $this->diffInSeconds($first) < $this->diffInSeconds($second) ? $first : $second;
    }

    /**
     * Get the farthest date from the instance.
     *
     * @param \Cake\Chronos\Chronos $first The instance to compare with.
     * @param \Cake\Chronos\Chronos $seocnd The instance to compare with.
     * @return self
     */
    public function farthest(Chronos $first, Chronos $seocnd): Chronos
    {
        return $this->diffInSeconds($first) > $this->diffInSeconds($seocnd) ? $first : $seocnd;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to compare with.
     * @return self
     */
    public function min(?Chronos $other = null): Chronos
    {
        $other = $other ?? static::now($this->tz);

        return $this->lessThan($other) ? $this : $other;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to compare with.
     * @return self
     */
    public function max(?Chronos $other = null): Chronos
    {
        $other = $other ?? static::now($this->tz);

        return $this->greaterThan($other) ? $this : $other;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to compare with.
     * @return static
     */
    public function average(?Chronos $other = null): static
    {
        $other ??= static::now($this->tz);

        return $this->addSeconds((int)($this->diffInSeconds($other, false) / 2));
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
        return in_array($this->dayOfWeek, Chronos::getWeekendDays(), true);
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
     * @param \Cake\Chronos\Chronos $other The instance to check against.
     * @return bool
     */
    public function isSameDay(Chronos $other): bool
    {
        return $this->toDateString() === $other->toDateString();
    }

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday(): bool
    {
        return $this->dayOfWeek === Chronos::SUNDAY;
    }

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday(): bool
    {
        return $this->dayOfWeek === Chronos::MONDAY;
    }

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday(): bool
    {
        return $this->dayOfWeek === Chronos::TUESDAY;
    }

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday(): bool
    {
        return $this->dayOfWeek === Chronos::WEDNESDAY;
    }

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday(): bool
    {
        return $this->dayOfWeek === Chronos::THURSDAY;
    }

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday(): bool
    {
        return $this->dayOfWeek === Chronos::FRIDAY;
    }

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday(): bool
    {
        return $this->dayOfWeek === Chronos::SATURDAY;
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
     * @param \Cake\Chronos\Chronos|null $other The instance to compare with or null to use current day.
     * @return bool
     */
    public function isBirthday(?Chronos $other = null): bool
    {
        $other ??= static::now($this->tz);

        return $this->format('md') === $other->format('md');
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

    /**
     * Get the difference by the given interval using a filter callable
     *
     * @param \DateInterval $interval An interval to traverse by
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffFiltered(
        DateInterval $interval,
        callable $callback,
        ?Chronos $other = null,
        bool $absolute = true
    ): int {
        $start = $this;
        $end = $other ?? static::now($this->tz);
        $inverse = false;

        if ($end < $start) {
            $start = $end;
            $end = $this;
            $inverse = true;
        }

        $period = new DatePeriod($start->native, $interval, $end->native);
        $vals = array_filter(iterator_to_array($period), function (DateTimeInterface $date) use ($callback) {
            return $callback(static::instance($date));
        });

        $diff = count($vals);

        return $inverse && !$absolute ? -$diff : $diff;
    }

    /**
     * Get the difference in years
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInYears(?Chronos $other = null, bool $absolute = true): int
    {
        $diff = $this->diff($other ?? static::now($this->tz), $absolute);

        return $diff->invert ? -$diff->y : $diff->y;
    }

    /**
     * Get the difference in months
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(?Chronos $other = null, bool $absolute = true): int
    {
        $diff = $this->diff($other ?? static::now($this->tz), $absolute);
        $months = $diff->y * Chronos::MONTHS_PER_YEAR + $diff->m;

        return $diff->invert ? -$months : $months;
    }

    /**
     * Get the difference in months ignoring the timezone. This means the months are calculated
     * in the specified timezone without converting to UTC first. This prevents the day from changing
     * which can change the month.
     *
     * For example, if comparing `2019-06-01 Asia/Tokyo` and `2019-10-01 Asia/Tokyo`,
     * the result would be 4 months instead of 3 when using normal `DateTime::diff()`.
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInMonthsIgnoreTimezone(?Chronos $other = null, bool $absolute = true): int
    {
        $utcTz = new DateTimeZone('UTC');
        $source = new static($this->format('Y-m-d H:i:s.u'), $utcTz);

        $other ??= static::now($this->tz);
        $other = new static($other->format('Y-m-d H:i:s.u'), $utcTz);

        return $source->diffInMonths($other, $absolute);
    }

    /**
     * Get the difference in weeks
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(?Chronos $other = null, bool $absolute = true): int
    {
        return (int)($this->diffInDays($other, $absolute) / Chronos::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInDays(?Chronos $other = null, bool $absolute = true): int
    {
        $diff = $this->diff($other ?? static::now($this->tz), $absolute);

        return $diff->invert ? -$diff->days : $diff->days;
    }

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(
        callable $callback,
        ?Chronos $other = null,
        bool $absolute = true
    ): int {
        return $this->diffFiltered(new DateInterval('P1D'), $callback, $other, $absolute);
    }

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(
        callable $callback,
        ?Chronos $other = null,
        bool $absolute = true
    ): int {
        return $this->diffFiltered(new DateInterval('PT1H'), $callback, $other, $absolute);
    }

    /**
     * Get the difference in weekdays
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(?Chronos $other = null, bool $absolute = true): int
    {
        return $this->diffInDaysFiltered(function (Chronos|ChronosDate $date) {
            return $date->isWeekday();
        }, $other, $absolute);
    }

    /**
     * Get the difference in weekend days using a filter
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(?Chronos $other = null, bool $absolute = true): int
    {
        return $this->diffInDaysFiltered(function (Chronos|ChronosDate $date) {
            return $date->isWeekend();
        }, $other, $absolute);
    }

    /**
     * Get the difference in hours
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInHours(?Chronos $other = null, bool $absolute = true): int
    {
        return (int)(
            $this->diffInSeconds($other, $absolute)
            / Chronos::SECONDS_PER_MINUTE
            / Chronos::MINUTES_PER_HOUR
        );
    }

    /**
     * Get the difference in minutes
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(?Chronos $other = null, bool $absolute = true): int
    {
        return (int)($this->diffInSeconds($other, $absolute) / Chronos::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds
     *
     * @param \Cake\Chronos\Chronos|null $other The instance to difference from.
     * @param bool $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(?Chronos $other = null, bool $absolute = true): int
    {
        $other ??= static::now($this->tz);
        $value = $other->getTimestamp() - $this->getTimestamp();

        return $absolute ? abs($value) : $value;
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight(): int
    {
        return $this->diffInSeconds($this->startOfDay());
    }

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay(): int
    {
        return $this->diffInSeconds($this->endOfDay());
    }

    /**
     * Convenience method for getting the remaining time from a given time.
     *
     * @param \Cake\Chronos\Chronos $other The date to get the remaining time from.
     * @return \DateInterval|bool The DateInterval object representing the difference between the two dates or FALSE on failure.
     */
    public static function fromNow(Chronos|ChronosDate $other): DateInterval|bool
    {
        $timeNow = new static();

        return $timeNow->diff($other);
    }

    /**
     * Get the difference in a human readable format.
     *
     * When comparing a value in the past to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the future to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the past to another value:
     * 1 hour before
     * 5 months before
     *
     * When comparing a value in the future to another value:
     * 1 hour after
     * 5 months after
     *
     * @param \Cake\Chronos\Chronos|null $other The datetime to compare with.
     * @param bool $absolute removes time difference modifiers ago, after, etc
     * @return string
     */
    public function diffForHumans(?Chronos $other = null, bool $absolute = false): string
    {
        return static::diffFormatter()->diffForHumans($this, $other, $absolute);
    }

    /**
     * Get a part of the object
     *
     * @param string $name The property name to read.
     * @return \DateTimeZone|string|float|int|bool The property value.
     * @throws \InvalidArgumentException
     */
    public function __get(string $name): string|float|int|bool|DateTimeZone
    {
        static $formats = [
            'year' => 'Y',
            'yearIso' => 'o',
            'month' => 'n',
            'day' => 'j',
            'hour' => 'G',
            'minute' => 'i',
            'second' => 's',
            'micro' => 'u',
            'microsecond' => 'u',
            'dayOfWeek' => 'N',
            'dayOfYear' => 'z',
            'weekOfYear' => 'W',
            'daysInMonth' => 't',
            'timestamp' => 'U',
        ];

        switch (true) {
            case isset($formats[$name]):
                return (int)$this->format($formats[$name]);

            case $name === 'dayOfWeekName':
                return $this->format('l');

            case $name === 'weekOfMonth':
                return (int)ceil($this->day / Chronos::DAYS_PER_WEEK);

            case $name === 'age':
                return $this->diffInYears();

            case $name === 'quarter':
                return (int)ceil($this->month / 3);

            case $name === 'offset':
                return $this->getOffset();

            case $name === 'offsetHours':
                return $this->getOffset() / Chronos::SECONDS_PER_MINUTE / Chronos::MINUTES_PER_HOUR;

            case $name === 'dst':
                return $this->format('I') === '1';

            case $name === 'local':
                return $this->offset === $this->setTimezone(date_default_timezone_get())->offset;

            case $name === 'utc':
                return $this->offset === 0;

            case $name === 'timezone' || $name === 'tz':
                return $this->getTimezone();

            case $name === 'timezoneName' || $name === 'tzName':
                return $this->getTimezone()->getName();

            default:
                throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $name));
        }
    }

    /**
     * Check if an attribute exists on the object
     *
     * @param string $name The property name to check.
     * @return bool Whether or not the property exists.
     */
    public function __isset(string $name): bool
    {
        try {
            $this->__get($name);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
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
            'time' => $this->format('Y-m-d H:i:s.u'),
            'timezone' => $this->getTimezone()->getName(),
        ];

        return $properties;
    }
}
