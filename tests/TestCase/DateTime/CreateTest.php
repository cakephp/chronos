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
use InvalidArgumentException;

class CreateTest extends TestCase
{
    public function testCreateReturnsDatingInstance()
    {
        $d = Chronos::create();
        $this->assertTrue($d instanceof Chronos);
    }

    public function testCreateWithDefaults()
    {
        $d = Chronos::create();
        $this->assertSame($d->timestamp, Chronos::now()->timestamp);
    }

    public function testCreateWithYear()
    {
        $d = Chronos::create(2012);
        $this->assertSame(2012, $d->year);
    }

    public function testCreateHandlesNegativeYear()
    {
        $d = Chronos::create(-1, 10, 12, 1, 2, 3);
        $this->assertDateTime($d, -1, 10, 12, 1, 2, 3);
    }

    public function testCreateHandlesFiveDigitsPositiveYears()
    {
        $c = Chronos::create(999999999, 10, 12, 1, 2, 3);
        $this->assertDateTime($c, 999999999, 10, 12, 1, 2, 3);
    }

    public function testCreateHandlesFiveDigitsNegativeYears()
    {
        $c = Chronos::create(-999999999, 10, 12, 1, 2, 3);
        $this->assertDateTime($c, -999999999, 10, 12, 1, 2, 3);
    }

    public function testCreateWithMonth()
    {
        $d = Chronos::create(null, 3);
        $this->assertSame(3, $d->month);
    }

    public function testCreateWithInvalidMonth()
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(null, -5);
    }

    public function testCreateMonthWraps()
    {
        $d = Chronos::create(2011, 0, 1, 0, 0, 0);
        $this->assertDateTime($d, 2010, 12, 1, 0, 0, 0);
    }

    public function testCreateWithDay()
    {
        $d = Chronos::create(null, null, 21);
        $this->assertSame(21, $d->day);
    }

    public function testCreateWithInvalidDay()
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(null, null, -4);
    }

    public function testCreateDayWraps()
    {
        $d = Chronos::create(2011, 1, 40, 0, 0, 0);
        $this->assertDateTime($d, 2011, 2, 9, 0, 0, 0);
    }

    public function testCreateWithHourAndDefaultMinSecToZero()
    {
        $d = Chronos::create(null, null, null, 14);
        $this->assertSame(14, $d->hour);
        $this->assertSame(0, $d->minute);
        $this->assertSame(0, $d->second);
    }

    public function testCreateWithInvalidHour()
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(null, null, null, -1);
    }

    public function testCreateHourWraps()
    {
        $d = Chronos::create(2011, 1, 1, 24, 0, 0);
        $this->assertDateTime($d, 2011, 1, 2, 0, 0, 0);
    }

    public function testCreateWithMinute()
    {
        $d = Chronos::create(null, null, null, null, 58);
        $this->assertSame(58, $d->minute);
    }

    public function testCreateWithInvalidMinute()
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(2011, 1, 1, 0, -2, 0);
    }

    public function testCreateMinuteWraps()
    {
        $d = Chronos::create(2011, 1, 1, 0, 62, 0);
        $this->assertDateTime($d, 2011, 1, 1, 1, 2, 0);
    }

    public function testCreateWithSecond()
    {
        $d = Chronos::create(null, null, null, null, null, 59);
        $this->assertSame(59, $d->second);
    }

    public function testCreateWithInvalidSecond()
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(null, null, null, null, null, -2);
    }

    public function testCreateSecondsWrap()
    {
        $d = Chronos::create(2012, 1, 1, 0, 0, 61);
        $this->assertDateTime($d, 2012, 1, 1, 0, 1, 1);
    }

    public function testCreateWithDateTimeZone()
    {
        $d = Chronos::create(2012, 1, 1, 0, 0, 0, 0, new DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateWithTimeZoneString()
    {
        $d = Chronos::create(2012, 1, 1, 0, 0, 0, 0, 'Europe/London');
        $this->assertDateTime($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromArray()
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
        $d = Chronos::createFromArray($values);
        $this->assertDateTime($d, 2012, 1, 1, 0, 13, 14, 123456);
        $this->assertSame('America/Toronto', $d->tzName);
    }

    public function testCreateFromArrayDateOnly()
    {
        $values = [
            'year' => 2012,
            'month' => '1',
            'day' => '1',
        ];
        $d = Chronos::createFromArray($values);
        $this->assertDateTime($d, 2012, 1, 1);
        $this->assertSame('America/Toronto', $d->tzName);
    }

    public function testCreateFromArrayTimeOnly()
    {
        $values = [
            'hour' => 12,
            'minute' => 13,
            'second' => '14',
            'microsecond' => 123456,
            'meridian' => 'am',
        ];
        $d = Chronos::createFromArray($values);
        $this->assertTime($d, 0, 13, 14, 123456);
        $this->assertSame('America/Toronto', $d->tzName);
    }
}
