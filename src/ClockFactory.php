<?php
declare(strict_types=1);

namespace Cake\Chronos;

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

use DateTimeImmutable;
use DateTimeZone;
use Psr\Clock\ClockInterface;

/**
 * PSR-20 Clock implementation.
 *
 * Provides a PSR-20 compatible clock that returns Chronos instances.
 * Useful for dependency injection and testing scenarios where you need
 * to control the current time.
 *
 * Example:
 * ```
 * $clock = new ClockFactory('UTC');
 * $now = $clock->now(); // Returns Chronos instance
 * ```
 */
class ClockFactory implements ClockInterface
{
    private readonly DateTimeZone|string|null $timezone;

    /**
     * Constructor.
     *
     * @param \DateTimeZone|string|null $timezone The timezone
     */
    public function __construct(DateTimeZone|string|null $timezone = null)
    {
        $this->timezone = $timezone;
    }

    /**
     * Returns the current time as a Chronos instance.
     *
     * The return type is DateTimeImmutable for PSR-20 compatibility,
     * but the actual returned instance is always Chronos.
     *
     * @return \Cake\Chronos\Chronos
     */
    public function now(): DateTimeImmutable
    {
        return Chronos::now($this->timezone);
    }
}
