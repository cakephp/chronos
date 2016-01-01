<?php
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

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * A mutable datetime instance that implements the ChronosInterface.
 *
 * This object can be mutated in place using any setter method,
 * or __set().
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
     * @param string|null $time Fixed or relative time
     * @param DateTimeZone|string|null $tz The timezone for the instance
     */
    public function __construct($time = 'now', $tz = null)
    {
        if ($tz !== null) {
            $tz = $tz instanceof DateTimeZone ? $tz : new DateTimeZone($tz);
        }

        if (static::$testNow === null) {
            return parent::__construct($time === null ? 'now' : $time, $tz);
        }

        $relative = static::hasRelativeKeywords($time);
        if (!empty($time) && $time !== 'now' && !$relative) {
            return parent::__construct($time, $tz);
        }

        $testInstance = clone static::getTestNow();
        if ($relative) {
            $testInstance = $testInstance;
            $testInstance = $testInstance->modify($time);
        }

        if ($tz !== $testInstance->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz === null ? date_default_timezone_get() : $tz);
        }

        $time = $testInstance->format('Y-m-d H:i:s.u');
        parent::__construct($time, $tz);
    }

    /**
     * Create a new immutable instance from current mutable instance.
     *
     * @return Chronos
     */
    public function toImmutable()
    {
        return Chronos::instance($this);
    }

    /**
     * Set a part of the ChronosInterface object
     *
     * @param string $name The property to set.
     * @param string|int|DateTimeZone $value The value to set.
     * @throws InvalidArgumentException
     * @return void
     */
    public function __set($name, $value)
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
}
