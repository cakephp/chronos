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

use Cake\Chronos\Test\TestCase\TestCase;

class CreateFromTimeTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDefaults($class)
    {
        $d = $class::createFromTime();
        $this->assertSame($d->timestamp, $class::create(null, null, null, null, null, null)->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDate($class)
    {
        $d = $class::createFromTime(23, 5, 21);
        $this->assertDateTime($d, $class::now()->year, $class::now()->month, $class::now()->day, 23, 5, 21);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithHour($class)
    {
        $d = $class::createFromTime(22);
        $this->assertSame(22, $d->hour);
        $this->assertSame(0, $d->minute);
        $this->assertSame(0, $d->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithMinute($class)
    {
        $d = $class::createFromTime(null, 5);
        $this->assertSame(5, $d->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithSecond($class)
    {
        $d = $class::createFromTime(null, null, 21);
        $this->assertSame(21, $d->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithMicrosecond($class)
    {
        $d = $class::createFromTime(null, null, null, 123456);
        $this->assertSame(123456, $d->microsecond);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithDateTimeZone($class)
    {
        $now = $class::now();
        $d = $class::createFromTime(12, 0, 0, 0, new \DateTimeZone('Europe/London'));
        $this->assertDateTime($d, $now->year, $now->month, $now->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithTimeZoneString($class)
    {
        $now = $class::now();
        $d = $class::createFromTime(12, 0, 0, 0, 'Europe/London');
        $this->assertDateTime($d, $now->year, $now->month, $now->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
