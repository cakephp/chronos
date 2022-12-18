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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTimeZone;

class CreateFromTimeTest extends TestCase
{
    public function testCreateFromDateWithDefaults()
    {
        $d = Chronos::createFromTime();
        $this->assertSame($d->timestamp, Chronos::create(null, null, null, null, null, null)->timestamp);
    }

    public function testCreateFromDate()
    {
        $d = Chronos::createFromTime(23, 5, 21);
        $this->assertDateTime($d, Chronos::now()->year, Chronos::now()->month, Chronos::now()->day, 23, 5, 21);
    }

    public function testCreateFromTimeWithHour()
    {
        $d = Chronos::createFromTime(22);
        $this->assertSame(22, $d->hour);
        $this->assertSame(0, $d->minute);
        $this->assertSame(0, $d->second);
    }

    public function testCreateFromTimeWithMinute()
    {
        $d = Chronos::createFromTime(null, 5);
        $this->assertSame(5, $d->minute);
    }

    public function testCreateFromTimeWithSecond()
    {
        $d = Chronos::createFromTime(null, null, 21);
        $this->assertSame(21, $d->second);
    }

    public function testCreateFromTimeWithMicrosecond()
    {
        $d = Chronos::createFromTime(null, null, null, 123456);
        $this->assertSame(123456, $d->microsecond);
    }

    public function testCreateFromTimeWithDateTimeZone()
    {
        $now = Chronos::now();
        $d = Chronos::createFromTime(12, 0, 0, 0, new DateTimeZone('Europe/London'));
        $this->assertDateTime($d, $now->year, $now->month, $now->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromTimeWithTimeZoneString()
    {
        $now = Chronos::now();
        $d = Chronos::createFromTime(12, 0, 0, 0, 'Europe/London');
        $this->assertDateTime($d, $now->year, $now->month, $now->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
