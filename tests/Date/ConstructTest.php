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
namespace Cake\Chronos\Test\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Test constructors for Date objects.
 */
class ConstructTest extends TestCase
{
    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromEmpty($class)
    {
        $c = $class::parse(null);
        $this->assertEquals('00:00:00', $c->format('H:i:s'));
        $this->assertEquals(date_default_timezone_get(), $c->tzName);

        $c = $class::parse('');
        $this->assertEquals('00:00:00', $c->format('H:i:s'));
        $this->assertEquals(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromEmptyWithTestNow($class)
    {
        $class::setTestNow($class::create(2001, 1, 1));

        $c = $class::parse(null);
        $this->assertEquals('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertEquals(date_default_timezone_get(), $c->tzName);

        $c = $class::parse('');
        $this->assertEquals('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertEquals(date_default_timezone_get(), $c->tzName);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromTimestamp($class)
    {
        $this->withTimezone('Europe/Berlin', function () use ($class) {
            $ts = 1454284800;
            $date = $class::createFromTimestamp($ts);

            $this->assertEquals('Europe/Berlin', $date->tzName);
            $this->assertEquals('2016-02-01', $date->format('Y-m-d'));
        });
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
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->second);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->micro);
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
        ];
    }

    /**
     * @dataProvider inputTimeProvider
     * @return void
     */
    public function testConstructWithTimeParts($time)
    {
        $dt = new Date($time);
        $this->assertEquals(8, $dt->month);
        $this->assertEquals(0, $dt->hour);
        $this->assertEquals(0, $dt->minute);
        $this->assertEquals(0, $dt->second);
    }

    /**
     * @dataProvider inputTimeProvider
     * @return void
     */
    public function testConstructMutableWithTimeParts($time)
    {
        $dt = new MutableDate($time);
        $this->assertEquals(8, $dt->month);
        $this->assertEquals(0, $dt->hour);
        $this->assertEquals(0, $dt->minute);
        $this->assertEquals(0, $dt->second);
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
        $this->assertEquals('00:00:00', $c->format('H:i:s'));

        $c = new $class('+10 minutes');
        $this->assertEquals('00:00:00', $c->format('H:i:s'));

        $c = new $class('2001-01-01 +7 days');
        $this->assertEquals('2001-01-08', $c->format('Y-m-d'));
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
        $this->assertEquals($london->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // now adjusted to London time
        $c = $class::today($londonTimezone);
        $this->assertEquals(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // London timezone is used instead of local timezone
        $c = new $class('2001-01-02 01:00:00', $londonTimezone);
        $this->assertEquals('2001-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when timezone is provided in time string
        $c = new $class('2001-01-01 23:00:00-400', $londonTimezone);
        $this->assertEquals('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // London timezone is ignored when DateTimeInterface instance is provided
        $c = new $class(new DateTimeImmutable('2001-01-01 23:00:00-400'), $londonTimezone);
        $this->assertEquals('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
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
        $this->assertEquals('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = new $class('+2 days', $londonTimezone);
        $this->assertEquals('2010-01-04 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = $class::today($londonTimezone);
        $this->assertEquals('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertEquals(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is adjusted to London time
        $c = $class::tomorrow($londonTimezone);
        $this->assertEquals('2010-01-03 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertEquals(Chronos::tomorrow($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        // TestNow is ignored when specific date is provided
        $c = new $class('2001-01-05 01:00:00', $londonTimezone);
        $this->assertEquals('2001-01-05 00:00:00', $c->format('Y-m-d H:i:s'));
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
        $this->assertEquals($Samoa->format('Y-m-d'), $c->format('Y-m-d'));
        $this->assertSame(date_default_timezone_get(), $c->tzName);

        date_default_timezone_set($savedTz);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testCreateFromExistingInstance($class)
    {
        $existingClass = new $class();
        self::assertInstanceOf($class, $existingClass);

        $newClass = new $class($existingClass);
        self::assertInstanceOf($class, $newClass);

        self::assertEquals((string)$existingClass, (string)$newClass);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testCreateFromDateTimeInterface($class)
    {
        $existingClass = new \DateTimeImmutable();
        $newClass = new $class($existingClass);
        self::assertInstanceOf($class, $newClass);
        self::assertEquals($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));

        $existingClass = new \DateTime();
        $newClass = new $class($existingClass);
        self::assertInstanceOf($class, $newClass);
        self::assertEquals($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));
    }
}
