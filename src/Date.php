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

/**
 * An immutable date object that converts all time components into 00:00:00.
 *
 * This class is useful when you want to represent a calendar date and ignore times.
 * This means that timezone changes take no effect as a calendar date exists in all timezones
 * in each respective date.
 */
class Date extends DateTimeImmutable implements ChronosInterface
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
    public function __construct($time = 'now', $tz = null)
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
            $testInstance = $testInstance->setTimezone($tz === null ? date_default_timezone_get() : $tz);
        }

        $time = $testInstance->format('Y-m-d 00:00:00');
        parent::__construct($time, $tz);
    }

    /**
     * Create a new mutable instance from current immutable instance.
     *
     * @return \Cake\Chronos\MutableDate
     */
    public function toMutable()
    {
        return MutableDate::instance($this);
    }
}
