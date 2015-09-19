<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Carbon;
use TestFixture;

class CreateFromTimeTest extends TestFixture
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
        $this->assertCarbon($d, $class::now()->year, Carbon::now()->month, Carbon::now()->day, 23, 5, 21);
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
    public function testCreateFromTimeWithDateTimeZone($class)
    {
        $d = $class::createFromTime(12, 0, 0, new \DateTimeZone('Europe/London'));
        $this->assertCarbon($d, $class::now()->year, Carbon::now()->month, Carbon::now()->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimeWithTimeZoneString($class)
    {
        $d = $class::createFromTime(12, 0, 0, 'Europe/London');
        $this->assertCarbon($d, $class::now()->year, Carbon::now()->month, Carbon::now()->day, 12, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
