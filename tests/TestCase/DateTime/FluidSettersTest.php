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
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

class FluidSettersTest extends TestCase
{
    public function testFluidYearSetter()
    {
        $d = Chronos::now();
        $d = $d->year(1995);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(1995, $d->year);
    }

    public function testFluidMonthSetter()
    {
        $d = Chronos::now();
        $d = $d->month(3);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(3, $d->month);
    }

    public function testFluidMonthSetterWithWrap()
    {
        $d = Chronos::createFromDate(2012, 8, 21);
        $d = $d->month(13);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(1, $d->month);
    }

    public function testFluidDaySetter()
    {
        $d = Chronos::now();
        $d = $d->day(2);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->day);
    }

    public function testFluidDaySetterWithWrap()
    {
        $d = Chronos::createFromDate(2000, 1, 1);
        $d = $d->day(32);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(1, $d->day);
    }

    public function testFluidSetDate()
    {
        $d = Chronos::createFromDate(2000, 1, 1);
        $d = $d->setDate(1995, 13, 32);
        $this->assertTrue($d instanceof Chronos);
        $this->assertDateTime($d, 1996, 2, 1);
    }

    public function testFluidChronosSetISODate()
    {
        $d = Chronos::createFromDate(2000, 1, 1);
        $d = $d->setISODate(2023, 17, 3);
        $this->assertTrue($d instanceof Chronos);
        $this->assertDateTime($d, 2023, 04, 26);
    }

    public function testFluidChronosDateSetISODate()
    {
        $d = ChronosDate::create(2000, 1, 1);
        $d = $d->setISODate(2023, 17, 3);
        $this->assertTrue($d instanceof ChronosDate);
        $this->assertDateTime($d, 2023, 04, 26);
    }

    public function testFluidHourSetter()
    {
        $d = Chronos::now();
        $d = $d->hour(2);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->hour);
    }

    public function testFluidHourSetterWithWrap()
    {
        $d = Chronos::now();
        $d = $d->hour(25);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(1, $d->hour);
    }

    public function testFluidMinuteSetter()
    {
        $d = Chronos::now();
        $d = $d->minute(2);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->minute);
    }

    public function testFluidMinuteSetterWithWrap()
    {
        $d = Chronos::now();
        $d = $d->minute(61);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(1, $d->minute);
    }

    public function testFluidSecondSetter()
    {
        $d = Chronos::now();
        $d = $d->second(2);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->second);
    }

    public function testFluidSecondSetterWithWrap()
    {
        $d = Chronos::now();
        $d = $d->second(62);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->second);
    }

    public function testFluidMicroecondSetter()
    {
        $d = Chronos::now();
        $second = $d->second;
        $d = $d->microsecond(2);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(2, $d->microsecond);
        $this->assertSame($second, $d->second);
    }

    public function testFluidSetTime()
    {
        $d = Chronos::createFromDate(2000, 1, 1);
        $d = $d->setTime(25, 61, 61);
        $this->assertTrue($d instanceof Chronos);
        $this->assertDateTime($d, 2000, 1, 2, 2, 2, 1);
    }

    public function testFluidTimestampSetter()
    {
        $d = Chronos::now();
        $d = $d->timestamp(10);
        $this->assertTrue($d instanceof Chronos);
        $this->assertSame(10, $d->timestamp);
    }
}
