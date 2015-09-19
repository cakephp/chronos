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
use TestCase;

class CreateTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateReturnsDatingInstance($class)
    {
        $d = $class::create();
        $this->assertTrue($d instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithDefaults($class)
    {
        $d = $class::create();
        $this->assertSame($d->timestamp, $class::now()->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithYear($class)
    {
        $d = $class::create(2012);
        $this->assertSame(2012, $d->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidYear($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(-3);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithMonth($class)
    {
        $d = $class::create(null, 3);
        $this->assertSame(3, $d->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidMonth($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(null, -5);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateMonthWraps($class)
    {
        $d = $class::create(2011, 0, 1, 0, 0, 0);
        $this->assertDateTime($d, 2010, 12, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithDay($class)
    {
        $d = $class::create(null, null, 21);
        $this->assertSame(21, $d->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidDay($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(null, null, -4);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateDayWraps($class)
    {
        $d = $class::create(2011, 1, 40, 0, 0, 0);
        $this->assertDateTime($d, 2011, 2, 9, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithHourAndDefaultMinSecToZero($class)
    {
        $d = $class::create(null, null, null, 14);
        $this->assertSame(14, $d->hour);
        $this->assertSame(0, $d->minute);
        $this->assertSame(0, $d->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidHour($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(null, null, null, -1);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateHourWraps($class)
    {
        $d = $class::create(2011, 1, 1, 24, 0, 0);
        $this->assertDateTime($d, 2011, 1, 2, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithMinute($class)
    {
        $d = $class::create(null, null, null, null, 58);
        $this->assertSame(58, $d->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidMinute($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(2011, 1, 1, 0, -2, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateMinuteWraps($class)
    {
        $d = $class::create(2011, 1, 1, 0, 62, 0);
        $this->assertDateTime($d, 2011, 1, 1, 1, 2, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithSecond($class)
    {
        $d = $class::create(null, null, null, null, null, 59);
        $this->assertSame(59, $d->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithInvalidSecond($class)
    {
        $this->setExpectedException('InvalidArgumentException');
        $d = $class::create(null, null, null, null, null, -2);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateSecondsWrap($class)
    {
        $d = $class::create(2012, 1, 1, 0, 0, 61);
        $this->assertDateTime($d, 2012, 1, 1, 0, 1, 1);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithDateTimeZone($class)
    {
        $d = $class::create(2012, 1, 1, 0, 0, 0, new \DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateWithTimeZoneString($class)
    {
        $d = $class::create(2012, 1, 1, 0, 0, 0, 'Europe/London');
        $this->assertDateTime($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
