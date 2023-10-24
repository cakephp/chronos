<?php

namespace Cake\Chronos;

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Daniel Opitz
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * PSR-20 Clock implementation.
 */
class ChronosClock implements ClockInterface
{
    /**
     * Returns the current time as a Chronos Object
     *
     * @return Chronos The current time
     */
    public function now(): DateTimeImmutable
    {
        return Chronos::now();
    }
}