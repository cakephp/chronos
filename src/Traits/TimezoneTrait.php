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
namespace Cake\Chronos\Traits;

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
    public function timezone($value)
    {
        return $this->setTimezone($value);
    }

    /**
     * Alias for setTimezone()
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function tz($value)
    {
        return $this->setTimezone($value);
    }

    /**
     * Set the instance's timezone from a string or object
     *
     * @param \DateTimeZone|string $value The DateTimeZone object or timezone name to use.
     * @return static
     */
    public function setTimezone($value)
    {
        return parent::setTimezone(static::safeCreateDateTimeZone($value));
    }
}
