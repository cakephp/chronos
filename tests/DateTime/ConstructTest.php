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

class ConstructTest extends TestCase
{

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
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
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
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
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
    public function testDefaultTimezone($class)
    {
        $c = new $class('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testConstructWithMicrosecondsAndOffset($class)
    {
        $c = new $class('2014-09-29 18:24:54.591767+02:00');
        $this->assertDateTime($c, 2014, 9, 29, 18, 24, 54);
        $this->assertSame(591767, $c->micro);
        $this->assertSame('+02:00', $c->getTimezone()->getName());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithDefaultTimezone($class)
    {
        $c = $class::parse('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezone($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new $class('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezone($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = $class::parse('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezoneWithString($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new $class('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezoneWithString($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = $class::parse('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }
}
