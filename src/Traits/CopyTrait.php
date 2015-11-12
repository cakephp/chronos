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
namespace Cake\Chronos\Traits;

/**
 * Provides methods for copying datetime objects.
 *
 * Expects that implementing classes provide a static `instance()` method.
 */
trait CopyTrait
{
    /**
     * Get a copy of the instance
     *
     * @return static
     */
    public function copy()
    {
        return static::instance($this);
    }
}
