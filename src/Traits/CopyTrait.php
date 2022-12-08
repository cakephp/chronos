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
namespace Cake\Chronos\Traits;

use Cake\Chronos\ChronosInterface;

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
    public function copy(): ChronosInterface
    {
        return static::instance($this);
    }
}
