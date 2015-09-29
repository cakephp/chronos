<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice. Provides various operator methods for datetime 
 * objects.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

use Cake\Chronos\ComparisonTrait;
use Cake\Chronos\FormattingTrait;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * A simple API extension for DateTimeInterface
 */
trait DateTimeTrait
{
    use ComparisonTrait;
    use FormattingTrait;

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static $days = [
        ChronosInterface::SUNDAY => 'Sunday',
        ChronosInterface::MONDAY => 'Monday',
        ChronosInterface::TUESDAY => 'Tuesday',
        ChronosInterface::WEDNESDAY => 'Wednesday',
        ChronosInterface::THURSDAY => 'Thursday',
        ChronosInterface::FRIDAY => 'Friday',
        ChronosInterface::SATURDAY => 'Saturday',
    ];

    /**
     * Terms used to detect if a time passed is a relative date for testing purposes
     *
     * @var array
     */
    protected static $relativeKeywords = [
        'this',
        'next',
        'last',
        'tomorrow',
        'yesterday',
        '+',
        '-',
        'first',
        'last',
        'ago',
    ];

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = ChronosInterface::DEFAULT_TO_STRING_FORMAT;


    /**
     * First day of week
     *
     * @var int
     */
    protected static $weekStartsAt = ChronosInterface::MONDAY;

    /**
     * Last day of week
     *
     * @var int
     */
    protected static $weekEndsAt = ChronosInterface::SUNDAY;

    /**
     * Days of weekend
     *
     * @var array
     */
    protected static $weekendDays = [ChronosInterface::SATURDAY, ChronosInterface::SUNDAY];

    /**
     * A test ChronosInterface instance to be returned when now instances are created
     *
     * @var ChronosInterface
     */
    protected static $testNow;

    /**
     * Creates a DateTimeZone from a string or a DateTimeZone
     *
     * @param DateTimeZone|string|null $object The value to convert.
     * @return DateTimeZone
     * @throws InvalidArgumentException
     */
    protected static function safeCreateDateTimeZone($object)
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
     * Create a ChronosInterface instance from a DateTimeInterface one
     *
     * @param DateTimeInterface $dt The datetime instance to convert.
     * @return static
     */
    public static function instance(DateTimeInterface $dt)
    {
        if ($dt instanceof static) {
            return clone $dt;
        }
        return new static($dt->format('Y-m-d H:i:s.u'), $dt->getTimeZone());
    }

    /**
     * Create a ChronosInterface instance from a string.  This is an alias for the
     * constructor that allows better fluent syntax as it allows you to do
     * ChronosInterface::parse('Monday next week')->fn() rather than
     * (new Chronos('Monday next week'))->fn()
     *
     * @param string $time The strtotime compatible string to parse
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name.
     * @return $this
     */
    public static function parse($time = null, $tz = null)
    {
        return new static($time, $tz);
    }

    /**
     * Get a ChronosInterface instance for the current date and time
     *
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name.
     * @return static
     */
    public static function now($tz = null)
    {
        return new static(null, $tz);
    }

    /**
     * Create a ChronosInterface instance for today
     *
     * @param DateTimeZone|string $tz The timezonze to use.
     * @return static
     */
    public static function today($tz = null)
    {
        return new static('midnight', $tz);
    }

    /**
     * Create a ChronosInterface instance for tomorrow
     *
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function tomorrow($tz = null)
    {
        return new static('tomorrow, midnight', $tz);
    }

    /**
     * Create a ChronosInterface instance for yesterday
     *
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function yesterday($tz = null)
    {
        return new static('yesterday, midnight', $tz);
    }

    /**
     * Create a ChronosInterface instance for the greatest supported date.
     *
     * @return ChronosInterface
     */
    public static function maxValue()
    {
        return static::createFromTimestamp(PHP_INT_MAX);
    }

    /**
     * Create a ChronosInterface instance for the lowest supported date.
     *
     * @return ChronosInterface
     */
    public static function minValue()
    {
        $max = PHP_INT_SIZE === 32 ? PHP_INT_MAX : PHP_INT_MAX / 10;
        return static::createFromTimestamp(~$max);
    }

    /**
     * Create a new ChronosInterface instance from a specific date and time.
     *
     * If any of $year, $month or $day are set to null their now() values
     * will be used.
     *
     * If $hour is null it will be set to its now() value and the default values
     * for $minute and $second will be their now() values.
     * If $hour is not null then the default values for $minute and $second
     * will be 0.
     *
     * @param int $year The year to create an instance with.
     * @param int $month The month to create an instance with.
     * @param int $day The day to create an instance with.
     * @param int $hour The hour to create an instance with.
     * @param int $minute The minute to create an instance with.
     * @param int $second The second to create an instance with.
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function create($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)
    {
        $year = ($year === null) ? date('Y') : $year;
        $month = ($month === null) ? date('n') : $month;
        $day = ($day === null) ? date('j') : $day;

        if ($hour === null) {
            $hour = date('G');
            $minute = ($minute === null) ? date('i') : $minute;
            $second = ($second === null) ? date('s') : $second;
        } else {
            $minute = ($minute === null) ? 0 : $minute;
            $second = ($second === null) ? 0 : $second;
        }

        return static::createFromFormat('Y-n-j G:i:s', sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second), $tz);
    }

    /**
     * Create a ChronosInterface instance from just a date. The time portion is set to now.
     *
     * @param int $year The year to create an instance with.
     * @param int $month The month to create an instance with.
     * @param int $day The day to create an instance with.
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromDate($year = null, $month = null, $day = null, $tz = null)
    {
        return static::create($year, $month, $day, null, null, null, $tz);
    }

    /**
     * Create a ChronosInterface instance from just a time. The date portion is set to today.
     *
     * @param int $hour The hour to create an instance with.
     * @param int $minute The minute to create an instance with.
     * @param int $second The second to create an instance with.
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromTime($hour = null, $minute = null, $second = null, $tz = null)
    {
        return static::create(null, null, null, $hour, $minute, $second, $tz);
    }

    /**
     * Create a ChronosInterface instance from a specific format
     *
     * @param string $format The date() compatible format string.
     * @param string $time The formatted date string to interpret.
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createFromFormat($format, $time, $tz = null)
    {
        if ($tz !== null) {
            $dt = parent::createFromFormat($format, $time, static::safeCreateDateTimeZone($tz));
        } else {
            $dt = parent::createFromFormat($format, $time);
        }

        if ($dt instanceof DateTimeInterface) {
            return static::instance($dt);
        }

        $errors = static::getLastErrors();
        throw new InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
    }

    /**
     * Create a ChronosInterface instance from a timestamp
     *
     * @param int $timestamp The timestamp to create an instance from.
     * @param DateTimeZone|string $tz The DateTimeZone object or timezone name the new instance should use.
     * @return static
     */
    public static function createFromTimestamp($timestamp, $tz = null)
    {
        return static::now($tz)->setTimestamp($timestamp);
    }

    /**
     * Create a ChronosInterface instance from an UTC timestamp
     *
     * @param int $timestamp The UTC timestamp to create an instance from.
     * @return static
     */
    public static function createFromTimestampUTC($timestamp)
    {
        return new static('@' . $timestamp);
    }

    /**
     * Get a copy of the instance
     *
     * @return static
     */
    public function copy()
    {
        return static::instance($this);
    }

    /**
     * Get a part of the ChronosInterface object
     *
     * @param string $name The property name to read.
     * @return string|int|DateTimeZone The property value.
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        switch (true) {
            case array_key_exists($name, $formats = [
                'year' => 'Y',
                'yearIso' => 'o',
                'month' => 'n',
                'day' => 'j',
                'hour' => 'G',
                'minute' => 'i',
                'second' => 's',
                'micro' => 'u',
                'dayOfWeek' => 'w',
                'dayOfYear' => 'z',
                'weekOfYear' => 'W',
                'daysInMonth' => 't',
                'timestamp' => 'U',
            ]):
                return (int)$this->format($formats[$name]);

            case $name === 'weekOfMonth':
                return (int)ceil($this->day / ChronosInterface::DAYS_PER_WEEK);

            case $name === 'age':
                return (int)$this->diffInYears();

            case $name === 'quarter':
                return (int)ceil($this->month / 3);

            case $name === 'offset':
                return $this->getOffset();

            case $name === 'offsetHours':
                return $this->getOffset() / ChronosInterface::SECONDS_PER_MINUTE / ChronosInterface::MINUTES_PER_HOUR;

            case $name === 'dst':
                return $this->format('I') == '1';

            case $name === 'local':
                return $this->offset == $this->copy()->setTimezone(date_default_timezone_get())->offset;

            case $name === 'utc':
                return $this->offset == 0;

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
    public function __isset($name)
    {
        try {
            $this->__get($name);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Set the instance's year
     *
     * @param int $value The year value.
     * @return static
     */
    public function year($value)
    {
        return $this->setDate($value, $this->month, $this->day);
    }

    /**
     * Set the instance's month
     *
     * @param int $value The month value.
     * @return static
     */
    public function month($value)
    {
        return $this->setDate($this->year, $value, $this->day);
    }

    /**
     * Set the instance's day
     *
     * @param int $value The day value.
     * @return static
     */
    public function day($value)
    {
        return $this->setDate($this->year, $this->month, $value);
    }

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return static
     */
    public function hour($value)
    {
        return $this->setTime($value, $this->minute, $this->second);
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return static
     */
    public function minute($value)
    {
        return $this->setTime($this->hour, $value, $this->second);
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return static
     */
    public function second($value)
    {
        return $this->setTime($this->hour, $this->minute, $value);
    }

    /**
     * Set the date and time all together
     *
     * @param int $year The year to set.
     * @param int $month The month to set.
     * @param int $day The day to set.
     * @param int $hour The hour to set.
     * @param int $minute The minute to set.
     * @param int $second The second to set.
     * @return static
     */
    public function setDateTime($year, $month, $day, $hour, $minute, $second = 0)
    {
        return $this->setDate($year, $month, $day)->setTime($hour, $minute, $second);
    }

    /**
     * Set the instance's timestamp
     *
     * @param int $value The timestamp value to set.
     * @return static
     */
    public function timestamp($value)
    {
        return parent::setTimestamp($value);
    }

    /**
     * Alias for setTimezone()
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function timezone($value)
    {
        return $this->setTimezone($value);
    }

    /**
     * Alias for setTimezone()
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function tz($value)
    {
        return $this->setTimezone($value);
    }

    /**
     * Set the instance's timezone from a string or object
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function setTimezone($value)
    {
        return parent::setTimezone(static::safeCreateDateTimeZone($value));
    }

    /**
     * Get the first day of week
     *
     * @return int
     */
    public static function getWeekStartsAt()
    {
        return static::$weekStartsAt;
    }

    /**
     * Set the first day of week
     *
     * @param int $day The day the week starts with.
     * @return void
     */
    public static function setWeekStartsAt($day)
    {
        static::$weekStartsAt = $day;
    }

    /**
     * Get the last day of week
     *
     * @return int
     */
    public static function getWeekEndsAt()
    {
        return static::$weekEndsAt;
    }

    /**
     * Set the first day of week
     *
     * @param int $day The day the week ends with.
     * @return void
     */
    public static function setWeekEndsAt($day)
    {
        static::$weekEndsAt = $day;
    }

    /**
     * Get weekend days
     *
     * @return array
     */
    public static function getWeekendDays()
    {
        return static::$weekendDays;
    }

    /**
     * Set weekend days
     *
     * @param array $days Which days are 'weekends'.
     * @return void
     */
    public static function setWeekendDays($days)
    {
        static::$weekendDays = $days;
    }

    /**
     * Set a ChronosInterface instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. ChronosInterface::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Chronos(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Chrono('now')
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * @param ChronosInterface $testNow The instance to use for all future instances.
     * @return void
     */
    public static function setTestNow(ChronosInterface $testNow = null)
    {
        static::$testNow = $testNow;
    }

    /**
     * Get the ChronosInterface instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return static the current instance used for testing
     */
    public static function getTestNow()
    {
        return static::$testNow;
    }

    /**
     * Determine if there is a valid test instance set. A valid test instance
     * is anything that is not null.
     *
     * @return bool true if there is a test instance, otherwise false
     */
    public static function hasTestNow()
    {
        return static::getTestNow() !== null;
    }

    /**
     * Determine if there is a relative keyword in the time string, this is to
     * create dates relative to now for test instances. e.g.: next tuesday
     *
     * @param string $time The time string to check.
     * @return bool true if there is a keyword, otherwise false
     */
    public static function hasRelativeKeywords($time)
    {
        // skip common format with a '-' in it
        if (preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1) {
            foreach (static::$relativeKeywords as $keyword) {
                if (stripos($time, $keyword) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines if the instance is a weekday
     *
     * @return bool
     */
    public function isWeekday()
    {
        return !$this->isWeekend();
    }

    /**
     * Determines if the instance is a weekend day
     *
     * @return bool
     */
    public function isWeekend()
    {
        return in_array($this->dayOfWeek, self::$weekendDays);
    }

    /**
     * Determines if the instance is yesterday
     *
     * @return bool
     */
    public function isYesterday()
    {
        return $this->toDateString() === static::yesterday($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is today
     *
     * @return bool
     */
    public function isToday()
    {
        return $this->toDateString() === static::now($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is tomorrow
     *
     * @return bool
     */
    public function isTomorrow()
    {
        return $this->toDateString() === static::tomorrow($this->tz)->toDateString();
    }

    /**
     * Determines if the instance is in the future, ie. greater (after) than now
     *
     * @return bool
     */
    public function isFuture()
    {
        return $this->gt(static::now($this->tz));
    }

    /**
     * Determines if the instance is in the past, ie. less (before) than now
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->lt(static::now($this->tz));
    }

    /**
     * Determines if the instance is a leap year
     *
     * @return bool
     */
    public function isLeapYear()
    {
        return $this->format('L') == '1';
    }

    /**
     * Checks if the passed in date is the same day as the instance current day.
     *
     * @param ChronosInterface $dt The instance to check against.
     * @return bool
     */
    public function isSameDay(ChronosInterface $dt)
    {
        return $this->toDateString() === $dt->toDateString();
    }

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday()
    {
        return $this->dayOfWeek === ChronosInterface::SUNDAY;
    }

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday()
    {
        return $this->dayOfWeek === ChronosInterface::MONDAY;
    }

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday()
    {
        return $this->dayOfWeek === ChronosInterface::TUESDAY;
    }

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday()
    {
        return $this->dayOfWeek === ChronosInterface::WEDNESDAY;
    }

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday()
    {
        return $this->dayOfWeek === ChronosInterface::THURSDAY;
    }

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday()
    {
        return $this->dayOfWeek === ChronosInterface::FRIDAY;
    }

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday()
    {
        return $this->dayOfWeek === ChronosInterface::SATURDAY;
    }

    /**
     * Returns true if this object represents a date within the current week
     *
     * @return bool
     */
    public function isThisWeek()
    {
        return static::now($this->getTimezone())->format('W o') == $this->format('W o');
    }

    /**
     * Returns true if this object represents a date within the current month
     *
     * @return bool
     */
    public function isThisMonth()
    {
        return static::now($this->getTimezone())->format('m Y') == $this->format('m Y');
    }

    /**
     * Returns true if this object represents a date within the current year
     *
     * @return bool
     */
    public function isThisYear()
    {
        return static::now($this->getTimezone())->format('Y') == $this->format('Y');
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYears($value)
    {
        return $this->modify((int)$value . ' year');
    }

    /**
     * Add a year to the instance
     *
     * @param int $value The number of years to add.
     * @return static
     */
    public function addYear($value = 1)
    {
        return $this->addYears($value);
    }

    /**
     * Remove a year from the instance
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYear($value = 1)
    {
        return $this->subYears($value);
    }

    /**
     * Remove years from the instance.
     *
     * @param int $value The number of years to remove.
     * @return static
     */
    public function subYears($value)
    {
        return $this->addYears(-1 * $value);
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonths($value)
    {
        return $this->modify((int)$value . ' month');
    }

    /**
     * Add a month to the instance
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonth($value = 1)
    {
        return $this->addMonths($value);
    }

    /**
     * Remove a month from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonth($value = 1)
    {
        return $this->subMonths($value);
    }

    /**
     * Remove months from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonths($value)
    {
        return $this->addMonths(-1 * $value);
    }

    /**
     * Add months without overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthsNoOverflow($value)
    {
        $date = $this->copy()->addMonths($value);

        if ($date->day != $this->day) {
            return $date
                ->day(1)
                ->subMonth()
                ->endOfMonth();
        }

        return $date;
    }

    /**
     * Add a month with no overflow to the instance
     *
     * @param int $value The number of months to add.
     * @return static
     */
    public function addMonthNoOverflow($value = 1)
    {
        return $this->addMonthsNoOverflow($value);
    }

    /**
     * Remove a month with no overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthNoOverflow($value = 1)
    {
        return $this->subMonthsNoOverflow($value);
    }

    /**
     * Remove months with no overflow from the instance
     *
     * @param int $value The number of months to remove.
     * @return static
     */
    public function subMonthsNoOverflow($value)
    {
        return $this->addMonthsNoOverflow(-1 * $value);
    }

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDays($value)
    {
        $value = (int)$value;
        return $this->modify("$value day");
    }

    /**
     * Add a day to the instance
     *
     * @param int $value The number of days to add.
     * @return static
     */
    public function addDay($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value day");
    }

    /**
     * Remove a day from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDay($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value day");
    }

    /**
     * Remove days from the instance
     *
     * @param int $value The number of days to remove.
     * @return static
     */
    public function subDays($value)
    {
        $value = (int)$value;
        return $this->modify("-$value day");
    }

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekdays($value)
    {
        $value = (int)$value;
        return $this->modify("$value weekday");
    }

    /**
     * Add a weekday to the instance
     *
     * @param int $value The number of weekdays to add.
     * @return static
     */
    public function addWeekday($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value weekday");
    }

    /**
     * Remove a weekday from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekday($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value weekday");
    }

    /**
     * Remove weekdays from the instance
     *
     * @param int $value The number of weekdays to remove.
     * @return static
     */
    public function subWeekdays($value)
    {
        $value = (int)$value;
        return $this->modify("-$value weekday");
    }

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeeks($value)
    {
        $value = (int)$value;
        return $this->modify("$value week");
    }

    /**
     * Add a week to the instance
     *
     * @param int $value The number of weeks to add.
     * @return static
     */
    public function addWeek($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value week");
    }

    /**
     * Remove a week from the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeek($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value week");
    }

    /**
     * Remove weeks to the instance
     *
     * @param int $value The number of weeks to remove.
     * @return static
     */
    public function subWeeks($value)
    {
        $value = (int)$value;
        return $this->modify("-$value week");
    }

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHours($value)
    {
        $value = (int)$value;
        return $this->modify("$value hour");
    }

    /**
     * Add an hour to the instance
     *
     * @param int $value The number of hours to add.
     * @return static
     */
    public function addHour($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value hour");
    }

    /**
     * Remove an hour from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHour($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value hour");
    }

    /**
     * Remove hours from the instance
     *
     * @param int $value The number of hours to remove.
     * @return static
     */
    public function subHours($value)
    {
        $value = (int)$value;
        return $this->modify("-$value hour");
    }

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinutes($value)
    {
        $value = (int)$value;
        return $this->modify("$value minute");
    }

    /**
     * Add a minute to the instance
     *
     * @param int $value The number of minutes to add.
     * @return static
     */
    public function addMinute($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value minute");
    }

    /**
     * Remove a minute from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinute($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value minute");
    }

    /**
     * Remove minutes from the instance
     *
     * @param int $value The number of minutes to remove.
     * @return static
     */
    public function subMinutes($value)
    {
        $value = (int)$value;
        return $this->modify("-$value minute");
    }

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSeconds($value)
    {
        $value = (int)$value;
        return $this->modify("$value second");
    }

    /**
     * Add a second to the instance
     *
     * @param int $value The number of seconds to add.
     * @return static
     */
    public function addSecond($value = 1)
    {
        $value = (int)$value;
        return $this->modify("$value second");
    }

    /**
     * Remove a second from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSecond($value = 1)
    {
        $value = (int)$value;
        return $this->modify("-$value second");
    }

    /**
     * Remove seconds from the instance
     *
     * @param int $value The number of seconds to remove.
     * @return static
     */
    public function subSeconds($value)
    {
        $value = (int)$value;
        return $this->modify("-$value second");
    }

    /**
     * Get the difference in years
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInYears(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return (int)$this->diff($dt, $abs)->format('%r%y');
    }

    /**
     * Get the difference in months
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMonths(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return $this->diffInYears($dt, $abs) * ChronosInterface::MONTHS_PER_YEAR + (int)$this->diff($dt, $abs)->format('%r%m');
    }

    /**
     * Get the difference in weeks
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeeks(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInDays($dt, $abs) / ChronosInterface::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDays(ChronosInterface $dt = null, $abs = true)
    {
        $dt = $dt === null ? static::now($this->tz) : $dt;
        return (int)$this->diff($dt, $abs)->format('%r%a');
    }

    /**
     * Get the difference in days using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInDaysFiltered(callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffFiltered(ChronosInterval::day(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in hours using a filter callable
     *
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHoursFiltered(callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffFiltered(ChronosInterval::hour(), $callback, $dt, $abs);
    }

    /**
     * Get the difference by the given interval using a filter callable
     *
     * @param ChronosInterval $ci An interval to traverse by
     * @param callable $callback The callback to use for filtering.
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffFiltered(ChronosInterval $ci, callable $callback, ChronosInterface $dt = null, $abs = true)
    {
        $start = $this;
        $end = $dt === null ? static::now($this->tz) : $dt;
        $inverse = false;

        if ($end < $start) {
            $start = $end;
            $end = $this;
            $inverse = true;
        }

        $period = new DatePeriod($start, $ci, $end);
        $vals = array_filter(iterator_to_array($period), function (DateTimeInterface $date) use ($callback) {
            return $callback(static::instance($date));
        });

        $diff = count($vals);

        return $inverse && !$abs ? -$diff : $diff;
    }

    /**
     * Get the difference in weekdays
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekdays(ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekday();
        }, $dt, $abs);
    }

    /**
     * Get the difference in weekend days using a filter
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInWeekendDays(ChronosInterface $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(function (ChronosInterface $date) {
            return $date->isWeekend();
        }, $dt, $abs);
    }

    /**
     * Get the difference in hours
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInHours(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInSeconds($dt, $abs) / ChronosInterface::SECONDS_PER_MINUTE / ChronosInterface::MINUTES_PER_HOUR);
    }

    /**
     * Get the difference in minutes
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInMinutes(ChronosInterface $dt = null, $abs = true)
    {
        return (int)($this->diffInSeconds($dt, $abs) / ChronosInterface::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds
     *
     * @param ChronosInterface $dt The instance to difference from.
     * @param bool $abs Get the absolute of the difference
     * @return int
     */
    public function diffInSeconds(ChronosInterface $dt = null, $abs = true)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;
        $value = $dt->getTimestamp() - $this->getTimestamp();

        return $abs ? abs($value) : $value;
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight()
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    /**
     * The number of seconds until 23:23:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay()
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }

    /**
     * Resets the time to 00:00:00
     *
     * @return static
     */
    public function startOfDay()
    {
        return $this->modify('midnight');
    }

    /**
     * Resets the time to 23:59:59
     *
     * @return static
     */
    public function endOfDay()
    {
        return $this->modify('23:59:59');
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @return static
     */
    public function startOfMonth()
    {
        return $this->modify('first day of this month midnight');
    }

    /**
     * Resets the date to end of the month and time to 23:59:59
     *
     * @return static
     */
    public function endOfMonth()
    {
        return $this->modify('last day of this month, 23:59:59');
    }

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @return static
     */
    public function startOfYear()
    {
        return $this->modify('first day of january midnight');
    }

    /**
     * Resets the date to end of the year and time to 23:59:59
     *
     * @return static
     */
    public function endOfYear()
    {
        return $this->modify('last day of december, 23:59:59');
    }

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @return static
     */
    public function startOfDecade()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE;
        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59
     *
     * @return static
     */
    public function endOfDecade()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_DECADE + ChronosInterface::YEARS_PER_DECADE - 1;
        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @return static
     */
    public function startOfCentury()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_CENTURY;
        return $this->modify("first day of january $year, midnight");
    }

    /**
     * Resets the date to end of the century and time to 23:59:59
     *
     * @return static
     */
    public function endOfCentury()
    {
        $year = $this->year - $this->year % ChronosInterface::YEARS_PER_CENTURY + ChronosInterface::YEARS_PER_CENTURY - 1;
        return $this->modify("last day of december $year, 23:59:59");
    }

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @return static
     */
    public function startOfWeek()
    {
        $dt = $this;
        if ($dt->dayOfWeek != static::$weekStartsAt) {
            $dt = $dt->previous(static::$weekStartsAt);
        }

        return $dt->startOfDay();
    }

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59
     *
     * @return static
     */
    public function endOfWeek()
    {
        $dt = $this;
        if ($dt->dayOfWeek != static::$weekEndsAt) {
            $dt = $dt->next(static::$weekEndsAt);
        }

        return $dt->endOfDay();
    }

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function next($dayOfWeek = null)
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
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function previous($dayOfWeek = null)
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
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfMonth($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];
        return $this->modify("first $day of this month, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfMonth($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];
        return $this->modify("last $day of this month, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfMonth($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfMonth();
        $check = $dt->format('Y-m');
        $dt = $dt->modify("+$nth " . static::$days[$dayOfWeek]);

        return ($dt->format('Y-m') === $check) ? $this->modify($dt) : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->quarter * 3 - 2)->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->quarter * 3)->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfQuarter($nth, $dayOfWeek)
    {
        $dt = $this->copy()->day(1)->month($this->quarter * 3);
        $lastMonth = $dt->month;
        $year = $dt->year;
        $dt = $dt->firstOfQuarter()->modify("+$nth" . static::$days[$dayOfWeek]);

        return ($lastMonth < $dt->month || $year !== $dt->year) ? false : $this->modify($dt);
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function firstOfYear($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];
        return $this->modify("first $day of january, midnight");
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function lastOfYear($dayOfWeek = null)
    {
        $day = $dayOfWeek === null ? 'day' : static::$days[$dayOfWeek];
        return $this->modify("last $day of december, midnight");
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. ChronosInterface::MONDAY.
     *
     * @param int $nth The offset to use.
     * @param int $dayOfWeek The day of the week to move to.
     * @return mixed
     */
    public function nthOfYear($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfYear()->modify("+$nth " . static::$days[$dayOfWeek]);
        return $this->year == $dt->year ? $this->modify($dt) : false;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function average(ChronosInterface $dt = null)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;

        return $this->addSeconds((int)($this->diffInSeconds($dt, false) / 2));
    }

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function isBirthday(ChronosInterface $dt)
    {
        return $this->format('md') === $dt->format('md');
    }

    /**
     * Check if instance of ChronosInterface is mutable.
     *
     * @return bool
     */
    public function isMutable()
    {
        return $this instanceof DateTime;
    }
}
