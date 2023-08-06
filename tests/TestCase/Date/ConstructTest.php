<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Test constructors for Date objects.
 */
class ConstructTest extends TestCase
{
    public function testCreateFromTimestampDeprecated()
    {
        $this->deprecated(function () {
            $date = ChronosDate::createFromTimestamp(time());
            $this->assertGreaterThanOrEqual(2022, $date->year);
        });

        $this->deprecated(function () {
            $date = ChronosDate::createFromTimestampUTC(time());
            $this->assertGreaterThanOrEqual(2022, $date->year);
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromEmpty($class)
    {
        $c = $class::parse(null);
        $this->assertSame('00:00:00', $c->format('H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        $c = $class::parse('');
        $this->assertSame('00:00:00', $c->format('H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromEmptyWithTestNow($class)
    {
        $class::setTestNow($class::create(2001, 1, 1));

        $c = $class::parse(null);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        $c = $class::parse('');
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromTimestamp($class)
    {
        $scenario = function () use ($class) {
            $ts = 1454284800;

            $date = $class::createFromTimestamp($ts);
            $this->assertSame('Europe/Berlin', $date->tzName);
            $this->assertSame('2016-02-01', $date->format('Y-m-d'));

            $date = new $class($ts);
            $this->assertSame('Europe/Berlin', $date->tzName);
            $this->assertSame('2016-02-01', $date->format('Y-m-d'));
        };
        $wrapped = $scenario;
        if ($class != MutableDate::class) {
            $wrapped = function () use ($scenario) {
                $this->deprecated($scenario);
            };
        }
        $this->withTimezone('Europe/Berlin', $wrapped);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreatesAnInstanceDefaultToNow($class)
    {
        $c = new $class();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, 0, 0, 0);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseCreatesAnInstanceDefaultToNow($class)
    {
        $c = $class::parse();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, 0, 0, 0);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testWithFancyString($class)
    {
        $c = new $class('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseWithFancyString($class)
    {
        $c = $class::parse('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseWithMicroSeconds($class)
    {
        $date = $class::parse('2016-12-08 18:06:46.510954');
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->second);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->micro);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testUsesDefaultTimezone($class)
    {
        $c = new $class('now');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseUsesDefaultTimezone($class)
    {
        $c = $class::parse('now');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testSettingTimezoneIgnored($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $c = new $class('now', $dtz);
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseSettingTimezoneIgnored($class)
    {
        $c = $class::parse('now', new DateTimeZone('Europe/London'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testSettingTimezoneWithStringIgnored($class)
    {
        $c = new $class('now', 'Asia/Tokyo');
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testParseSettingTimezoneWithStringIgnored($class)
    {
        $c = $class::parse('now', 'Asia/Tokyo');
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

    /**
     * @dataProvider inputTimeProvider
     * @return void
     */
    public function testConstructMutableWithTimeParts($time)
    {
        $dt = new MutableDate($time);
        $this->assertSame(8, $dt->month);
        $this->assertSame(0, $dt->hour);
        $this->assertSame(0, $dt->minute);
        $this->assertSame(0, $dt->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testConstructWithTestNow($class)
    {
        $class::setTestNow($class::create(2001, 1, 1));
        $date = new $class('+2 days');
        $this->assertDateTime($date, 2001, 1, 3);

        $date = new $class('2015-12-12');
        $this->assertDateTime($date, 2015, 12, 12);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testConstructWithTestNowNoMutation($class)
    {
        $class::setTestNow($class::create(2001, 1, 1));
        $date = new $class('+2 days');
        $this->assertDateTime($date, 2001, 1, 3, 0, 0, 0);

        $date = new $class();
        $this->assertNotEquals('2001-01-03', $date->format('Y-m-d'));
        $class::setTestNow(null);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testConstructWithRelative($class)
    {
        $c = new $class('+7 days');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new $class('+10 minutes');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new $class('2001-01-01 +7 days');
        $this->assertSame('2001-01-08', $c->format('Y-m-d'));
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testConstructWithLocalTimezone($class)
    {
        $londonTimezone = new DateTimeZone('Europe/London');

        // now adjusted to London time
        // This test could have different results depending on when now is
        $c = new $class('now', $londonTimezone);
        $london = new DateTimeImmutable('now', $londonTimezone);
        $this->assertSame($london->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // now adjusted to London time
        $c = $class::today($londonTimezone);
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // London timezone is used instead of local timezone
        $c = new $class('2001-01-02 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when timezone is provided in time string
        $c = new $class('2001-01-01 23:00:00-400', $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when DateTimeInterface instance is provided
        $c = new $class(new DateTimeImmutable('2001-01-01 23:00:00-400'), $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testConstructWithLocalTimezoneTestNow($class)
    {
        $class::setTestNow(new Chronos('2010-01-01 23:00:00'));

        $londonTimezone = new DateTimeZone('Europe/London');

        // TestNow is adjusted to London time
        $c = new $class('now', $londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = new $class('+2 days', $londonTimezone);
        $this->assertSame('2010-01-04 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = $class::today($londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = $class::tomorrow($londonTimezone);
        $this->assertSame('2010-01-03 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::tomorrow($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is ignored when specific date is provided
        $c = new $class('2001-01-05 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-05 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);
    }

    /**
     * This tests with a large difference between local timezone and
     * timezone provided as parameter.  This is to help guarantee a date
     * change would occur so the tests are more consistent.
     *
     * @dataProvider dateClassProvider
     */
    public function testConstructWithLargeTimezoneChange($class)
    {
        $savedTz = date_default_timezone_get();
        date_default_timezone_set('Pacific/Kiritimati');

        $samoaTimezone = new DateTimeZone('Pacific/Samoa');

        // Pacific/Samoa -11:00 is used intead of local timezone +14:00
        $c = $class::today($samoaTimezone);
        $Samoa = new DateTimeImmutable('now', $samoaTimezone);
        $this->assertSame($Samoa->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        date_default_timezone_set($savedTz);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testCreateFromExistingInstance($class)
    {
        $existingClass = new $class();
        $this->assertInstanceOf($class, $existingClass);

        $newClass = new $class($existingClass);
        $this->assertInstanceOf($class, $newClass);

        $this->assertSame((string)$existingClass, (string)$newClass);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromDateTimeInterface($class)
    {
        $existingClass = new \DateTimeImmutable();
        $newClass = new $class($existingClass);
        $this->assertInstanceOf($class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));

        $existingClass = new \DateTime();
        $newClass = new $class($existingClass);
        $this->assertInstanceOf($class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromFormat($class)
    {
        $date = $class::createFromFormat('Y-m-d P', '2014-02-01 Asia/Tokyo');
        $this->assertSame('2014-02-01 00:00:00 America/Toronto', $date->format('Y-m-d H:i:s e'));
    }
}
