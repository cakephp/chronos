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
     * No-op method.
     *
     * Timezones have no effect on calendar dates.
     *
     * @param DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setTimezone($value)
    {
        return $this;
    }
}
