<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Carbon;
use TestFixture;

class ConstructTest extends TestFixture
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreatesAnInstanceDefaultToNow($class)
    {
        $c   = new $class();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertCarbon($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseCreatesAnInstanceDefaultToNow($class)
    {
        $c   = $class::parse();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertCarbon($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWithFancyString($class)
    {
        $c = new $class('first day of January 2008');
        $this->assertCarbon($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithFancyString($class)
    {
        $c = $class::parse('first day of January 2008');
        $this->assertCarbon($c, 2008, 1, 1, 0, 0, 0);
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
        $timezone                 = 'Europe/London';
        $dtz                      = new \DateTimeZone($timezone);
        $dt                       = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = $dt->format('I');

        $c = new $class('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(0 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezone($class)
    {
        $timezone                 = 'Europe/London';
        $dtz                      = new \DateTimeZone($timezone);
        $dt                       = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = $dt->format('I');

        $c = $class::parse('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(0 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezoneWithString($class)
    {
        $timezone                 = 'Asia/Tokyo';
        $dtz                      = new \DateTimeZone($timezone);
        $dt                       = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = $dt->format('I');

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
        $timezone                 = 'Asia/Tokyo';
        $dtz                      = new \DateTimeZone($timezone);
        $dt                       = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = $dt->format('I');

        $c = $class::parse('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }
}
