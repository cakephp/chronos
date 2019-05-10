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

use Cake\Chronos\Chronos;
use Cake\Chronos\MutableDateTime;
use InvalidArgumentException;
use TestCase;

class SettersTest extends TestCase
{
    public function testYearSetter()
    {
        $d = MutableDateTime::now();
        $d->year = 1995;
        $this->assertSame(1995, $d->year);
    }

    public function testMonthSetter()
    {
        $d = MutableDateTime::now();
        $d->month = 3;
        $this->assertSame(3, $d->month);
    }

    public function testMonthSetterWithWrap()
    {
        $d = MutableDateTime::now();
        $d->month = 13;
        $this->assertSame(1, $d->month);
    }

    public function testDaySetter()
    {
        $d = MutableDateTime::now();
        $d->day = 2;
        $this->assertSame(2, $d->day);
    }

    public function testDaySetterWithWrap()
    {
        $d = MutableDateTime::createFromDate(2012, 8, 5);
        $d->day = 32;
        $this->assertSame(1, $d->day);
    }

    public function testHourSetter()
    {
        $d = MutableDateTime::now();
        $d->hour = 2;
        $this->assertSame(2, $d->hour);
    }

    public function testHourSetterWithWrap()
    {
        $d = MutableDateTime::now();
        $d->hour = 25;
        $this->assertSame(1, $d->hour);
    }

    public function testMinuteSetter()
    {
        $d = MutableDateTime::now();
        $d->minute = 2;
        $this->assertSame(2, $d->minute);
    }

    public function testMinuteSetterWithWrap()
    {
        $d = MutableDateTime::now();
        $d->minute = 65;
        $this->assertSame(5, $d->minute);
    }

    public function testSecondSetter()
    {
        $d = MutableDateTime::now();
        $d->second = 2;
        $this->assertSame(2, $d->second);
    }

    public function testTimeSetter()
    {
        $d = MutableDateTime::now();
        $d->setTime(1, 1, 1);
        $this->assertSame(1, $d->second);
        $d->setTime(1, 1);
        $this->assertSame(0, $d->second);
    }

    public function testTimeSetterWithChaining()
    {
        $d = MutableDateTime::now();
        $d->setTime(2, 2, 2)->setTime(1, 1, 1);
        $this->assertInstanceOf(MutableDateTime::class, $d);
        $this->assertSame(1, $d->second);
        $d->setTime(2, 2, 2)->setTime(1, 1);
        $this->assertInstanceOf(MutableDateTime::class, $d);
        $this->assertSame(0, $d->second);
    }

    public function testTimeSetterWithZero()
    {
        $d = MutableDateTime::now();
        $d->setTime(1, 1);
        $this->assertSame(0, $d->second);
    }

    public function testSetDateAfterStringCreation()
    {
        $d = new MutableDateTime('first day of this month');
        $this->assertEquals(1, $d->day);
        $d->setDate($d->year, $d->month, 12);
        $this->assertEquals(12, $d->day);

        $d = new Chronos('first day of this month');
        $this->assertEquals(1, $d->day);
        $this->assertEquals(12, $d->setDate($d->year, $d->month, 12)->day);
    }

    public function testDateTimeSetter()
    {
        $d = MutableDateTime::now();
        $d->setDateTime($d->year, $d->month, $d->day, 1, 1, 1);
        $this->assertSame(1, $d->second);
    }

    public function testDateTimeSetterWithZero()
    {
        $d = MutableDateTime::now();
        $d->setDateTime($d->year, $d->month, $d->day, 1, 1);
        $this->assertSame(0, $d->second);
    }

    public function testDateTimeSetterWithChaining()
    {
        $d = MutableDateTime::now();
        $d->setDateTime(2013, 9, 24, 17, 4, 29);
        $this->assertInstanceOf(MutableDateTime::class, $d);
        $d->setDateTime(2014, 10, 25, 18, 5, 30);
        $this->assertInstanceOf(MutableDateTime::class, $d);
        $this->assertDateTime($d, 2014, 10, 25, 18, 5, 30);
    }

    public function testSecondSetterWithWrap()
    {
        $d = MutableDateTime::now();
        $d->second = 65;
        $this->assertSame(5, $d->second);
    }

    public function testTimestampSetter()
    {
        $d = MutableDateTime::now();
        $d->timestamp = 10;
        $this->assertSame(10, $d->timestamp);

        $d->setTimestamp(11);
        $this->assertSame(11, $d->timestamp);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown or bad timezone
     * @return void
     */
    public function testSetTimezoneWithInvalidTimezone()
    {
        $d = MutableDateTime::now();
        $d->setTimezone('sdf');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown or bad timezone
     * @return void
     */
    public function testTimezoneWithInvalidTimezone()
    {
        $d = MutableDateTime::now();
        $d->timezone = 'sdf';
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown or bad timezone
     * @return void
     */
    public function testTzWithInvalidTimezone()
    {
        $d = MutableDateTime::now();
        $d->tz('sdf');
    }

    public function testSetTimezoneUsingString()
    {
        $d = MutableDateTime::now();
        $d->setTimezone('America/Toronto');
        $this->assertSame('America/Toronto', $d->tzName);
    }

    public function testTimezoneUsingString()
    {
        $d = MutableDateTime::now();
        $d->timezone = 'America/Toronto';
        $this->assertSame('America/Toronto', $d->tzName);

        $d->timezone('America/Vancouver');
        $this->assertSame('America/Vancouver', $d->tzName);
    }

    public function testTzUsingString()
    {
        $d = MutableDateTime::now();
        $d->tz = 'America/Toronto';
        $this->assertSame('America/Toronto', $d->tzName);

        $d->tz('America/Vancouver');
        $this->assertSame('America/Vancouver', $d->tzName);
    }

    public function testSetTimezoneUsingDateTimeZone()
    {
        $d = MutableDateTime::now();
        $d->setTimezone(new \DateTimeZone('America/Toronto'));
        $this->assertSame('America/Toronto', $d->tzName);
    }

    public function testTimezoneUsingDateTimeZone()
    {
        $d = MutableDateTime::now();
        $d->timezone = new \DateTimeZone('America/Toronto');
        $this->assertSame('America/Toronto', $d->tzName);

        $d->timezone(new \DateTimeZone('America/Vancouver'));
        $this->assertSame('America/Vancouver', $d->tzName);
    }

    public function testTzUsingDateTimeZone()
    {
        $d = MutableDateTime::now();
        $d->tz = new \DateTimeZone('America/Toronto');
        $this->assertSame('America/Toronto', $d->tzName);

        $d->tz(new \DateTimeZone('America/Vancouver'));
        $this->assertSame('America/Vancouver', $d->tzName);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSetter()
    {
        $d = MutableDateTime::now();
        $d->doesNotExit = 'bb';
    }

    public function testSetTimeFromTimeString()
    {
        $d = MutableDateTime::now();
        $d->setTimeFromTimeString('09:15:30');
        $this->assertSame(9, $d->hour);
        $this->assertSame(15, $d->minute);
        $this->assertSame(30, $d->second);
    }

    public function testDateSetter()
    {
        $dateString = '1977-05-05 04:00:00';
        $d = new MutableDateTime();
        $this->assertNotSame(1977, $d->year);
        $d->date = $dateString;
        $this->assertSame($dateString, $d->toDateTimeString());
    }

    public function testInvalidDateSetter()
    {
        $this->expectException(\Exception::class);
        $message = 'DateTimeImmutable::__construct(): Failed to parse time string (invalid) at position 0 (i): The timezone could not be found in the database';
        $this->expectExceptionMessage($message);
        $dateString = 'invalid';
        $d = new MutableDateTime();
        $d->date = $dateString;
    }

    public function testOpisSerializeChronos()
    {
        $dateString = '1977-05-05 04:00:00';
        $d = new Chronos($dateString);
        $unserialized = \Opis\Closure\unserialize(\Opis\Closure\serialize($d));
        $this->assertSame($dateString, $unserialized->toDateTimeString());
        $this->assertSame(Chronos::class, get_class($unserialized));
    }

    public function testOpisSerializeMutableDateTime()
    {
        $dateString = '1977-05-05 04:00:00';
        $d = new MutableDateTime($dateString);

        $serialized = \Opis\Closure\serialize($d);
        $unserialized = \Opis\Closure\unserialize($serialized);
        $this->assertSame($dateString, $unserialized->toDateTimeString());
        $this->assertSame(MutableDateTime::class, get_class($unserialized));
    }

    public function testSerializeMutableDateTime()
    {
        $dateString = '1977-05-05 04:00:00';
        $d = new MutableDateTime($dateString);

        $serialized = serialize($d);
        $unserialized = unserialize($serialized);
        $this->assertSame($dateString, $unserialized->toDateTimeString());
        $this->assertSame(MutableDateTime::class, get_class($unserialized));
    }

    public function testSerializeChronos()
    {
        $dateString = '1977-05-05 04:00:00';
        $d = new Chronos($dateString);

        $serialized = serialize($d);
        $unserialized = unserialize($serialized);
        $this->assertSame($dateString, $unserialized->toDateTimeString());
        $this->assertSame(Chronos::class, get_class($unserialized));
    }
}
