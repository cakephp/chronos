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

use DateTimeImmutable;
use DateTimeZone;
use LogicException;

/**
 * An immutable date object that does converts all time components
 * into 00:00:00.
 *
 * This class is useful when you want to represent a calendar date and ignore times.
 * This means that timezone changes take no effect as a calendar date exists in all timezones
 * in each respective date.
 */
class Date extends DateTimeImmutable implements ChronosInterface
{
    use ComparisonTrait;
    use DifferenceTrait;
    use FactoryTrait;
    use FormattingTrait;
    use MagicPropertyTrait;
    use ModifierTrait;
    use RelativeKeywordTrait;
    use TestingAidTrait;

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = 'Y-m-d';

    /**
     * Create a new Immutable Date instance.
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
     * @param DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = null, $tz = null)
    {
        $tz = new DateTimeZone('UTC');
        if (static::$testNow === null) {
            $time = $this->stripTime($time);
            return parent::__construct($time, $tz);
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            $time = $this->stripTime($time);
            return parent::__construct($time, $tz);
        }

        $testInstance = static::getTestNow();
        if ($relative) {
            $testInstance = $testInstance->modify($time);
        }

        if ($tz !== $testInstance->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz);
        }

        $time = $testInstance->format('Y-m-d 00:00:00');
        parent::__construct($time, $tz);
    }

    /**
     * Removes the time components from an input string.
     *
     * Used to ensure constructed objects always lack time.
     *
     * @param string|int $time The input time. Integer values will be assumed
     *   to be in UTC. The 'now' and '' values will use the current local time.
     * @return string The date component of $time.
     */
    protected function stripTime($time)
    {
        if (is_int($time) || ctype_digit($time)) {
            return gmdate('Y-m-d 00:00:00', $time);
        }
        if ($time === null || $time === 'now' || $time === '') {
            return date('Y-m-d 00:00:00');
        }
        return preg_replace('/\d{1,2}:\d{1,2}:\d{1,2}/', '00:00:00', $time);
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function timezone($value)
    {
        return $this;
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function tz($value)
    {
        return $this;
    }

    /**
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return $this
     */
    public function setTimezone($value)
    {
        return $this;
    }

    /**
     * Overloaded to throw exceptions for time components.
     *
     * You cannot set the time on calendar dates.
     *
     * @param string $relative The relative change to make.
     * @return static A modified Date
     * @throws \LogicException When time components are changed.
     */
    public function modify($relative)
    {
        if (preg_match('/hour|minute|second/', $relative)) {
            throw new LogicException('You cannot modify the time component of a calendar date.');
        }
        return parent::modify($relative);
    }

    /**
     * Set the instance's hour
     *
     * @param int $value The hour value.
     * @return void
     * @throws \LogicException You cannot modify the time on a calendar date.
     */
    public function hour($value)
    {
        throw new LogicException('You cannot modify the time component of a calendar date.');
    }

    /**
     * Set the instance's minute
     *
     * @param int $value The minute value.
     * @return void
     * @throws \LogicException You cannot modify the time on a calendar date.
     */
    public function minute($value)
    {
        throw new LogicException('You cannot modify the time component of a calendar date.');
    }

    /**
     * Set the instance's second
     *
     * @param int $value The seconds value.
     * @return void
     * @throws \LogicException You cannot modify the time on a calendar date.
     */
    public function second($value)
    {
        throw new LogicException('You cannot modify the time component of a calendar date.');
    }
}
