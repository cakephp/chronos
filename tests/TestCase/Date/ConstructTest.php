<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Test constructors for Date objects.
 */
class ConstructTest extends TestCase
{
    public function testCreateFromEmpty()
    {
        $c = ChronosDate::parse(null);
        $this->assertSame('00:00:00', $c->format('H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        $c = ChronosDate::parse('');
        $this->assertSame('00:00:00', $c->format('H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testCreateFromEmptyWithTestNow()
    {
        ChronosDate::setTestNow(ChronosDate::create(2001, 1, 1));

        $c = ChronosDate::parse(null);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        $c = ChronosDate::parse('');
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testCreateFromTimestamp()
    {
        $this->withTimezone('Europe/Berlin', function () {
            $ts = 1454284800;

            $date = ChronosDate::createFromTimestamp($ts);
            $this->assertSame('Europe/Berlin', $date->tzName);
            $this->assertSame('2016-02-01', $date->format('Y-m-d'));

            $date = new ChronosDate($ts);
            $this->assertSame('Europe/Berlin', $date->tzName);
            $this->assertSame('2016-02-01', $date->format('Y-m-d'));
        });
    }

    public function testCreatesAnInstanceDefaultToNow()
    {
        $c = new ChronosDate();
        $now = ChronosDate::now();
        $this->assertInstanceOf(ChronosDate::class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, 0, 0, 0);
    }

    public function testParseCreatesAnInstanceDefaultToNow()
    {
        $c = ChronosDate::parse();
        $now = ChronosDate::now();
        $this->assertInstanceOf(ChronosDate::class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, 0, 0, 0);
    }

    public function testWithFancyString()
    {
        $c = new ChronosDate('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithFancyString()
    {
        $c = ChronosDate::parse('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithMicroSeconds()
    {
        $date = ChronosDate::parse('2016-12-08 18:06:46.510954');
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->second);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->micro);
    }

    public function testUsesDefaultTimezone()
    {
        $c = new ChronosDate('now');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testParseUsesDefaultTimezone()
    {
        $c = ChronosDate::parse('now');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testSettingTimezoneIgnored()
    {
        $timezone = 'Europe/London';
        $dtz = new DateTimeZone($timezone);
        $c = new ChronosDate('now', $dtz);
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testParseSettingTimezoneIgnored()
    {
        $c = ChronosDate::parse('now', new DateTimeZone('Europe/London'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testSettingTimezoneWithStringIgnored()
    {
        $c = new ChronosDate('now', 'Asia/Tokyo');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testParseSettingTimezoneWithStringIgnored()
    {
        $c = ChronosDate::parse('now', 'Asia/Tokyo');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * Data provider for constructor testing.
     *
     * @return array
     */
    public function inputTimeProvider()
    {
        return [
            ['@' . strtotime('2015-08-19 22:24:32')],
            [strtotime('2015-08-19 22:24:32')],
            ['2015-08-19 10:00:00'],
            ['2015-08-19T10:00:00+05:00'],
            ['Monday, 15-Aug-2005 15:52:01 UTC'],
            ['Mon, 15 Aug 05 15:52:01 +0000'],
            ['Monday, 15-Aug-05 15:52:01 UTC'],
            ['Mon, 15 Aug 05 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['2005-08-15T15:52:01+00:00'],
            ['20050815'],
        ];
    }

    /**
     * @dataProvider inputTimeProvider
     * @return void
     */
    public function testConstructWithTimeParts($time)
    {
        $dt = new ChronosDate($time);
        $this->assertSame(8, $dt->month);
        $this->assertSame(0, $dt->hour);
        $this->assertSame(0, $dt->minute);
        $this->assertSame(0, $dt->second);
    }

    public function testConstructWithTestNow()
    {
        ChronosDate::setTestNow(ChronosDate::create(2001, 1, 1));
        $date = new ChronosDate('+2 days');
        $this->assertDateTime($date, 2001, 1, 3);

        $date = new ChronosDate('2015-12-12');
        $this->assertDateTime($date, 2015, 12, 12);
    }

    public function testConstructWithTestNowNoMutation()
    {
        ChronosDate::setTestNow(ChronosDate::create(2001, 1, 1));
        $date = new ChronosDate('+2 days');
        $this->assertDateTime($date, 2001, 1, 3, 0, 0, 0);

        $date = new ChronosDate();
        $this->assertNotEquals('2001-01-03', $date->format('Y-m-d'));
        ChronosDate::setTestNow(null);
    }

    public function testConstructWithRelative()
    {
        $c = new ChronosDate('+7 days');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new ChronosDate('+10 minutes');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new ChronosDate('2001-01-01 +7 days');
        $this->assertSame('2001-01-08', $c->format('Y-m-d'));
    }

    public function testConstructWithLocalTimezone()
    {
        $londonTimezone = new DateTimeZone('Europe/London');

        // now adjusted to London time
        // This test could have different results depending on when now is
        $c = new ChronosDate('now', $londonTimezone);
        $london = new DateTimeImmutable('now', $londonTimezone);
        $this->assertSame($london->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // now adjusted to London time
        $c = ChronosDate::today($londonTimezone);
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // London timezone is used instead of local timezone
        $c = new ChronosDate('2001-01-02 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when timezone is provided in time string
        $c = new ChronosDate('2001-01-01 23:00:00-400', $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when DateTimeInterface instance is provided
        $c = new ChronosDate(new DateTimeImmutable('2001-01-01 23:00:00-400'), $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    public function testConstructWithLocalTimezoneTestNow()
    {
        ChronosDate::setTestNow(new Chronos('2010-01-01 23:00:00'));

        $londonTimezone = new DateTimeZone('Europe/London');

        // TestNow is adjusted to London time
        $c = new ChronosDate('now', $londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = new ChronosDate('+2 days', $londonTimezone);
        $this->assertSame('2010-01-04 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = ChronosDate::today($londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = ChronosDate::tomorrow($londonTimezone);
        $this->assertSame('2010-01-03 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::tomorrow($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is ignored when specific date is provided
        $c = new ChronosDate('2001-01-05 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-05 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * This tests with a large difference between local timezone and
     * timezone provided as parameter.  This is to help guarantee a date
     * change would occur so the tests are more consistent.
     */
    public function testConstructWithLargeTimezoneChange()
    {
        $savedTz = date_default_timezone_get();
        date_default_timezone_set('Pacific/Kiritimati');

        $samoaTimezone = new DateTimeZone('Pacific/Samoa');

        // Pacific/Samoa -11:00 is used intead of local timezone +14:00
        $c = ChronosDate::today($samoaTimezone);
        $Samoa = new DateTimeImmutable('now', $samoaTimezone);
        $this->assertSame($Samoa->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        date_default_timezone_set($savedTz);
    }

    public function testCreateFromExistingInstance()
    {
        $existingClass = new ChronosDate();
        $this->assertInstanceOf(ChronosDate::class, $existingClass);

        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);

        $this->assertSame((string)$existingClass, (string)$newClass);
    }

    public function testCreateFromDateTimeInterface()
    {
        $existingClass = new DateTimeImmutable();
        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));

        $existingClass = new DateTime();
        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));
    }

    public function testCreateFromFormat()
    {
        $date = ChronosDate::createFromFormat('Y-m-d P', '2014-02-01 Asia/Tokyo');
        $this->assertSame('2014-02-01 00:00:00 America/Toronto', $date->format('Y-m-d H:i:s e'));
    }
}
