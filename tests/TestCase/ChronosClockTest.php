<?php
declare(strict_types=1);

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

namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosClock;
use DateTimeImmutable;
use DateTimeZone;

class ChronosClockTest extends TestCase
{
    public function testNow(): void
    {
        Chronos::setTestNow('2001-01-31 12:13:14.123456');

        $clock = new ChronosClock();
        $now = $clock->now();

        $this->assertSame('2001-01-31', $now->toDateString());
    }

    public function testConstructWithTimezone(): void
    {
        Chronos::setTestNow('2024-01-31 12:13:14.123456');

        $londonTimezone = new DateTimeZone('Europe/London');
        $clock = new ChronosClock($londonTimezone);
        $now = $clock->now();

        $this->assertSame('2024-01-31', $now->toDateString());
        $this->assertSame('Europe/London', $now->getTimezone()->getName());
    }
}
