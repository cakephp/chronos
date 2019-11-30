<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\DateTime;

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
    public function testCreateHandlesNegativeYear($class)
    {
        $d = $class::create(-1, 10, 12, 1, 2, 3);
        $this->assertDateTime($d, -1, 10, 12, 1, 2, 3);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateHandlesFiveDigitsPositiveYears($class)
    {
        $c = $class::create(999999999, 10, 12, 1, 2, 3);
        $this->assertDateTime($c, 999999999, 10, 12, 1, 2, 3);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateHandlesFiveDigitsNegativeYears($class)
    {
        $c = $class::create(-999999999, 10, 12, 1, 2, 3);
        $this->assertDateTime($c, -999999999, 10, 12, 1, 2, 3);
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
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testCreateWithInvalidMonth($class)
    {
        $class::create(null, -5);
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
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testCreateWithInvalidDay($class)
    {
        $class::create(null, null, -4);
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
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testCreateWithInvalidHour($class)
    {
        $class::create(null, null, null, -1);
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
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testCreateWithInvalidMinute($class)
    {
        $class::create(2011, 1, 1, 0, -2, 0);
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
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testCreateWithInvalidSecond($class)
    {
        $class::create(null, null, null, null, null, -2);
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

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromArray($class)
    {
        $values = [
            'year' => 2012,
            'month' => '1',
            'day' => '1',
            'hour' => 12,
            'minute' => 13,
            'second' => '14',
            'microsecond' => 123456,
            'meridian' => 'am',
        ];
        $d = $class::createFromArray($values);
        $this->assertDateTime($d, 2012, 1, 1, 0, 13, 14, 123456);
        $this->assertSame('America/Toronto', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromArrayDateOnly($class)
    {
        $values = [
            'year' => 2012,
            'month' => '1',
            'day' => '1',
        ];
        $d = $class::createFromArray($values);
        $this->assertDateTime($d, 2012, 1, 1);
        $this->assertSame('America/Toronto', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromArrayTimeOnly($class)
    {
        $values = [
            'hour' => 12,
            'minute' => 13,
            'second' => '14',
            'microsecond' => 123456,
            'meridian' => 'am',
        ];
        $d = $class::createFromArray($values);
        $this->assertTime($d, 0, 13, 14, 123456);
        $this->assertSame('America/Toronto', $d->tzName);
    }
}
