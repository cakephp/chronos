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

use DateTimeImmutable;
use DateTimeZone;

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
 */
class Chronos extends DateTimeImmutable implements ChronosInterface
{
    use Traits\ComparisonTrait;
    use Traits\DifferenceTrait;
    use Traits\FactoryTrait;
    use Traits\FormattingTrait;
    use Traits\MagicPropertyTrait;
    use Traits\ModifierTrait;
    use Traits\RelativeKeywordTrait;
    use Traits\TimezoneTrait;

    /**
     * A test ChronosInterface instance to be returned when now instances are created
     *
     * There is a single test now for all date/time classes provided by Chronos.
     * This aims to emulate stubbing out 'now' which is a single global fact.
     *
     * @var \Cake\Chronos\ChronosInterface|null
     */
    protected static $testNow;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = ChronosInterface::DEFAULT_TO_STRING_FORMAT;

    /**
     * Create a new Chronos instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string|int|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = 'now', $tz = null)
    {
        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        static::$_lastErrors = [];
        $testNow = static::getTestNow();
        if ($testNow === null) {
            if ($time instanceof \DateTimeInterface) {
                $time = $time->format('Y-m-d H:i:s.u');
            }
            parent::__construct($time ?? 'now', $tz);

            return;
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            parent::__construct($time, $tz);

            return;
        }

        $testNow = clone $testNow;
        $relativetime = static::isTimeExpression($time);
        if (!$relativetime && $tz !== $testNow->getTimezone()) {
            $testNow = $testNow->setTimezone($tz ?? date_default_timezone_get());
        }

        if ($relative) {
            $testNow = $testNow->modify($time);
        }

        $time = $testNow->format('Y-m-d H:i:s.u');
        parent::__construct($time, $tz);
    }

    /**
     * Create a new mutable instance from current immutable instance.
     *
     * @return \Cake\Chronos\MutableDateTime
     */
    public function toMutable(): MutableDateTime
    {
        return MutableDateTime::instance($this);
    }

    /**
     * Get a copy of the instance
     *
     * @return static
     */
    public function copy(): ChronosInterface
    {
        return clone $this;
    }

    /**
     * Set a ChronosInterface instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. ChronosInterface::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Chronos(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Chronos('now')
     *   - When a string containing the desired time is passed to ChronosInterface::parse()
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * @param \Cake\Chronos\ChronosInterface|string|null $testNow The instance to use for all future instances.
     * @return void
     */
    public static function setTestNow($testNow = null): void
    {
        static::$testNow = is_string($testNow) ? static::parse($testNow) : $testNow;
    }

    /**
     * Get the ChronosInterface instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return \Cake\Chronos\ChronosInterface|null The current instance used for testing
     */
    public static function getTestNow(): ?ChronosInterface
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
