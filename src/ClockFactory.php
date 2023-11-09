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
 */
class ClockFactory implements ClockInterface
{
    private DateTimeZone|string|null $timezone;

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
     * Returns the current time object.
     *
     * @return \Cake\Chronos\Chronos The current time
     */
    public function now(): DateTimeImmutable
    {
        return Chronos::now($this->timezone);
    }
}
