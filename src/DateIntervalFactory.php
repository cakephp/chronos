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

use DateInterval;

/**
 * Factory class for creating DateInterval instances.
 */
class DateIntervalFactory
{
    public const PERIOD_PREFIX = 'P';

    public const PERIOD_YEARS = 'Y';

    public const PERIOD_MONTHS = 'M';

    public const PERIOD_WEEKS = 'W';

    public const PERIOD_DAYS = 'D';

    public const PERIOD_TIME_PREFIX = 'T';

    public const PERIOD_HOURS = 'H';

    public const PERIOD_MINUTES = 'M';

    public const PERIOD_SECONDS = 'S';

    /**
     * Create a new DateInterval instance from specific values.
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
    public static function create(
        ?int $years = null,
        ?int $months = null,
        ?int $weeks = null,
        ?int $days = null,
        ?int $hours = null,
        ?int $minutes = null,
        ?int $seconds = null,
        ?int $microseconds = null,
    ): DateInterval {
        $spec = static::PERIOD_PREFIX;

        if ($years) {
            $spec .= $years . static::PERIOD_YEARS;
        }
        if ($months) {
            $spec .= $months . static::PERIOD_MONTHS;
        }
        if ($weeks) {
            $spec .= $weeks . static::PERIOD_WEEKS;
        }
        if ($days) {
            $spec .= $days . static::PERIOD_DAYS;
        }

        if ($hours || $minutes || $seconds) {
            $spec .= static::PERIOD_TIME_PREFIX;
            if ($hours) {
                $spec .= $hours . static::PERIOD_HOURS;
            }
            if ($minutes) {
                $spec .= $minutes . static::PERIOD_MINUTES;
            }
            if ($seconds) {
                $spec .= $seconds . static::PERIOD_SECONDS;
            }
        }

        $instance = new DateInterval($spec);

        if ($microseconds) {
            $instance->f = $microseconds / 1000000;
        }

        return $instance;
    }
}
