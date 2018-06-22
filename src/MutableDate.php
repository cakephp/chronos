<?php
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

use DateTime;
use DateTimeZone;

/**
 * A mutable date object that converts all time components into 00:00:00.
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
 * @property-read int $timestamp seconds since the Unix Epoch
 * @property-read DateTimeZone $timezone the current timezone
 * @property-read DateTimeZone $tz alias of timezone
 * @property-read int $micro
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
 * @property-read string  $timezoneName
 * @property-read string  $tzName
 */
class MutableDate extends DateTime implements ChronosInterface
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
     * Create a new mutable Date instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * Date instances lack time components, however due to limitations in PHP's
     * internal Datetime object the time will always be set to 00:00:00, and the
     * timezone will always be UTC. Normalizing the timezone allows for
     * subtraction/addition to have deterministic results.
     *
     * @param string|null $time Fixed or relative time
     */
    public function __construct($time = 'now')
    {
        $tz = new DateTimeZone('UTC');

        $testNow = Chronos::getTestNow();
        if ($testNow === null) {
            $time = $this->stripTime($time);
            parent::__construct($time, $tz);

            return;
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            $time = $this->stripTime($time);

            parent::__construct($time, $tz);

            return;
        }

        $testNow = clone $testNow;
        if ($relative) {
            $time = $this->stripRelativeTime($time);
            if (strlen($time) > 0) {
                $testNow = $testNow->modify($time);
            }
        }

        if ($tz !== $testNow->getTimezone()) {
            $testNow = $testNow->setTimezone($tz === null ? date_default_timezone_get() : $tz);
        }

        $time = $testNow->format('Y-m-d 00:00:00');
        parent::__construct($time, $tz);
    }

    /**
     * Create a new immutable instance from current mutable instance.
     *
     * @return \Cake\Chronos\Date
     */
    public function toImmutable()
    {
        return Date::instance($this);
    }

    /**
     * Return properties for debugging.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $properties = [
            'date' => $this->format('Y-m-d'),
            'hasFixedNow' => static::hasTestNow()
        ];

        return $properties;
    }
}
