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
namespace Cake\Chronos;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * A mutable datetime instance that implements the ChronosInterface.
 *
 * This object can be mutated in place using any setter method,
 * or __set().
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
 * @property-read \DateTimeZone|string $timezone the current timezone
 * @property-read \DateTimeZone|string $tz alias of timezone
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
class MutableDateTime extends DateTime implements ChronosInterface
{
    use Traits\ComparisonTrait;
    use Traits\DifferenceTrait;
    use Traits\FactoryTrait;
    use Traits\FormattingTrait;
    use Traits\MagicPropertyTrait;
    use Traits\ModifierTrait;
    use Traits\RelativeKeywordTrait;
    use Traits\TestingAidTrait;
    use Traits\TimezoneTrait;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = ChronosInterface::DEFAULT_TO_STRING_FORMAT;

    /**
     * Create a new MutableDateTime instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param \DateTimeInterface|string|int|null $time Fixed or relative time
     * @param \DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = 'now', $tz = null)
    {
        if (is_int($time)) {
            parent::__construct('@' . $time);

            return;
        }

        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->format('Y-m-d H:i:s.u');
        }

        $testNow = Chronos::getTestNow();
        if ($testNow === null) {
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
     * Create a new immutable instance from current mutable instance.
     *
     * @return \Cake\Chronos\Chronos
     */
    public function toImmutable(): Chronos
    {
        return Chronos::instance($this);
    }

    /**
     * Set a part of the ChronosInterface object
     *
     * @param string $name The property to set.
     * @param string|int|\DateTimeZone $value The value to set.
     * @throws \InvalidArgumentException
     * @return void
     */
    public function __set(string $name, $value): void
    {
        switch ($name) {
            case 'year':
                $this->year($value);
                break;

            case 'month':
                $this->month($value);
                break;

            case 'day':
                $this->day($value);
                break;

            case 'hour':
                $this->hour($value);
                break;

            case 'minute':
                $this->minute($value);
                break;

            case 'second':
                $this->second($value);
                break;

            case 'timestamp':
                $this->timestamp($value);
                break;

            case 'timezone':
            case 'tz':
                $this->timezone($value);
                break;

            default:
                throw new InvalidArgumentException(sprintf("Unknown setter '%s'", $name));
        }
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
