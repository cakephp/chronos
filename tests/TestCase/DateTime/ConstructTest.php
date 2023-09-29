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
use Cake\Chronos\ChronosTime;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;

class ConstructTest extends TestCase
{
    public function testCreateFromTimestamp()
    {
        $ts = 1454284800;
        $time = new Chronos($ts);
        $this->assertSame('+00:00', $time->tzName);
        $this->assertSame('2016-02-01 00:00:00', $time->format('Y-m-d H:i:s'));
        $this->assertSame($ts, $time->getTimestamp());

        $ts = '1454284800';
        $time = new Chronos($ts);
        $this->assertSame('+00:00', $time->tzName);
        $this->assertSame('2016-02-01 00:00:00', $time->format('Y-m-d H:i:s'));
        $this->assertSame((int)$ts, $time->getTimestamp());
    }

    public function testCreatesAnInstanceDefaultToNow()
    {
        $c = new Chronos();
        $now = Chronos::now();
        $this->assertInstanceOf(Chronos::class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    public function testParseCreatesAnInstanceDefaultToNow()
    {
        $c = Chronos::parse();
        $now = Chronos::now();
        $this->assertInstanceOf(Chronos::class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    public function testWithFancyString()
    {
        $c = new Chronos('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithFancyString()
    {
        $c = Chronos::parse('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testDefaultTimezone()
    {
        $c = new Chronos('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    public function testConstructWithMicrosecondsAndOffset()
    {
        $c = new Chronos('2014-09-29 18:24:54.591767+02:00');
        $this->assertDateTime($c, 2014, 9, 29, 18, 24, 54);
        $this->assertSame(591767, $c->micro);
        $this->assertSame('+02:00', $c->getTimezone()->getName());
    }

    public function testParseWithDefaultTimezone()
    {
        $c = Chronos::parse('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    public function testSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new Chronos('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testParseSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = Chronos::parse('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new Chronos('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testParseSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = Chronos::parse('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testCreateFromExistingInstance()
    {
        $existingClass = new Chronos();
        $newClass = new Chronos($existingClass);
        $this->assertSame((string)$existingClass, (string)$newClass);
    }

    public function testCreateFromChronosDate()
    {
        $date = new ChronosDate('2021-01-01');
        $chronos = new Chronos($date);
        $this->assertSame('2021-01-01 00:00:00', $chronos->format('Y-m-d H:i:s'));
    }

    public function testCreateFromChronosTime()
    {
        $time = new ChronosTime('20:14:12.123456');
        $chronos = new Chronos($time);
        $this->assertSame((string)Chronos::parse('20:14:12.123456'), (string)$chronos);

        $chronos = new Chronos($time, 'Asia/Tokyo');
        $this->assertSame((string)Chronos::parse('20:14:12.123456'), (string)$chronos);
        $this->assertSame('Asia/Tokyo', $chronos->tzName);

        $chronos = Chronos::parse($time);
        $this->assertSame((string)Chronos::parse('20:14:12.123456'), (string)$chronos);

        $chronos = Chronos::parse($time, 'Asia/Tokyo');
        $this->assertSame((string)Chronos::parse('20:14:12.123456'), (string)$chronos);
        $this->assertSame('Asia/Tokyo', $chronos->tzName);
    }

    public function testCreateFromDateTimeInterface()
    {
        $existingClass = new DateTimeImmutable();
        $newClass = new Chronos($existingClass);
        $this->assertSame($existingClass->format('Y-m-d H:i:s.u'), $newClass->format('Y-m-d H:i:s.u'));

        $existingClass = new DateTime();
        $newClass = new Chronos($existingClass);
        $this->assertSame($existingClass->format('Y-m-d H:i:s.u'), $newClass->format('Y-m-d H:i:s.u'));

        $existingClass = new DateTime('2019-01-15 00:15:22.139302');
        $newClass = new Chronos($existingClass);
        $this->assertDateTime($newClass, 2019, 01, 15, 0, 15, 22);
        $this->assertSame(139302, $newClass->micro);
    }
}
