<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice. Provides various operator methods for datetime
 * objects.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Traits;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosInterface;
use ReturnTypeWillChange;

/**
 * Methods for modifying/reading timezone data.
 */
trait TimezoneTrait
{
    /**
     * Alias for setTimezone()
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function timezone($value): ChronosInterface
    {
        trigger_error('2.5 timezone() is deprecated. Use setTimezone() instead.', E_USER_DEPRECATED);

        return $this->setTimezone($value);
    }

    /**
     * Alias for setTimezone()
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function tz($value): ChronosInterface
    {
        trigger_error('2.5 tz() is deprecated. Use setTimezone() instead.', E_USER_DEPRECATED);

        return $this->setTimezone($value);
    }

    /**
     * Set the instance's timezone from a string or object
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    #[ReturnTypeWillChange]
    public function setTimezone($value): ChronosInterface
    {
        if (static::class === ChronosDate::class) {
            trigger_error('2.5 setTimezone() will be removed in 3.x.', E_USER_DEPRECATED);
        }

        return parent::setTimezone(static::safeCreateDateTimeZone($value));
    }
}
