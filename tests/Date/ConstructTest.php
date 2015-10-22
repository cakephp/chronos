<?php
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

use Cake\Chronos\Date;
use DateTimeZone;
use TestCase;

/**
 * Test constructors for Date objects.
 */
class ConstructTest extends TestCase
{
    public function classNameProvider()
    {
        return [[Date::class]];
    }

    /**
     * @dataProvider classNameProvider
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
     * @dataProvider classNameProvider
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
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWithFancyString($class)
    {
        $c = new $class('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithFancyString($class)
    {
        $c = $class::parse('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testUsesUTC($class)
    {
        $c = new $class('now');
        $this->assertSame('UTC', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseUsesUTC($class)
    {
        $c = $class::parse('now');
        $this->assertSame('UTC', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezoneIgnored($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $c = new $class('now', $dtz);
        $this->assertSame('UTC', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezoneIgnored($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $c = $class::parse('now', $dtz);

        $this->assertSame('UTC', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezoneWithStringIgnored($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);

        $c = new $class('now', $timezone);
        $this->assertSame('UTC', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezoneWithStringIgnored($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $c = $class::parse('now', $timezone);
        $this->assertSame('UTC', $c->tzName);
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
}
