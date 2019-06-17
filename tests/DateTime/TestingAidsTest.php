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
use Cake\Chronos\Date;
use Cake\Chronos\MutableDate;
use Cake\Chronos\MutableDateTime;
use DateTimeZone;
use TestCase;

class TestingAidsTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTestingAidsWithTestNowNotSet($class)
    {
        $class::setTestNow();

        $this->assertFalse($class::hasTestNow());
        $this->assertNull($class::getTestNow());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTestingAidsWithTestNowSet($class)
    {
        $notNow = $class::yesterday();
        $class::setTestNow($notNow);

        $this->assertTrue($class::hasTestNow());
        $this->assertEquals($notNow, $class::getTestNow());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTestingAidsWithTestNowSetToString($class)
    {
        $class::setTestNow('2016-11-23');
        $this->assertTrue($class::hasTestNow());
        $this->assertEquals($class::getTestNow(), $class::parse('2016-11-23'));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testConstructorWithTestValueSet($class)
    {
        $notNow = $class::yesterday();
        $class::setTestNow($notNow);

        $this->assertEquals($notNow, new $class());
        $this->assertEquals($notNow, new $class(null));
        $this->assertEquals($notNow, new $class(''));
        $this->assertEquals($notNow, new $class('now'));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNowWithTestValueSet($class)
    {
        $notNow = $class::yesterday();
        $class::setTestNow($notNow);

        $this->assertEquals($notNow, $class::now());
    }

    /**
     * Ensure that using test now doesn't mutate test now.
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNowNoMutateDateTime($class)
    {
        $value = '2018-06-21 10:11:12';
        $notNow = new MutableDateTime($value);
        $class::setTestNow($notNow);

        $instance = new $class('-10 minutes');
        $this->assertSame('10:01:12', $instance->format('H:i:s'));

        $instance = new $class('-10 minutes');
        $this->assertSame('10:01:12', $instance->format('H:i:s'));
    }

    /**
     * Ensure that using test now doesn't mutate test now.
     *
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testNowNoMutateDate($class)
    {
        $value = '2018-06-21 10:11:12';
        $notNow = new MutableDateTime($value);
        $class::setTestNow($notNow);

        $instance = new $class('-1 day');
        $this->assertSame('2018-06-20 00:00:00', $instance->format('Y-m-d H:i:s'));

        $instance = new $class('-1 day');
        $this->assertSame('2018-06-20 00:00:00', $instance->format('Y-m-d H:i:s'));
    }

    /**
     * Ensure that setting a datetime into test now doesn't violate date semantics
     *
     * Modifying date instances by hours should not change the date.
     *
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testNowTestDateTimeConstraints($class)
    {
        $value = '2018-06-21 10:11:12';
        $notNow = new MutableDateTime($value);
        $class::setTestNow($notNow);

        $instance = new $class('-23 hours');
        $this->assertSame('2018-06-21 00:00:00', $instance->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithTestValueSet($class)
    {
        $notNow = $class::yesterday();
        $class::setTestNow($notNow);

        $this->assertEquals($notNow, $class::parse());
        $this->assertEquals($notNow, $class::parse(null));
        $this->assertEquals($notNow, $class::parse(''));
        $this->assertEquals($notNow, $class::parse('now'));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseRelativeWithTestValueSet($class)
    {
        $notNow = $class::parse('2013-09-01 05:15:05');
        $class::setTestNow($notNow);

        $this->assertSame('2013-09-01 06:30:00', $class::parse('6:30')->toDateTimeString());
        $this->assertSame('2013-09-01 06:30:00', $class::parse('6:30:00')->toDateTimeString());
        $this->assertSame('2013-09-01 06:30:00', $class::parse('06:30:00')->toDateTimeString());

        $this->assertSame('2013-09-01 05:10:05', $class::parse('5 minutes ago')->toDateTimeString());

        $this->assertSame('2013-08-25 05:15:05', $class::parse('1 week ago')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', $class::parse('tomorrow')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', $class::parse('today')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', $class::parse('midnight')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', $class::parse('yesterday')->toDateTimeString());

        $this->assertSame('2013-09-02 05:15:05', $class::parse('+1 day')->toDateTimeString());
        $this->assertSame('2013-08-31 05:15:05', $class::parse('-1 day')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', $class::parse('next monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', $class::parse('next tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', $class::parse('next wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', $class::parse('next thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', $class::parse('next friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', $class::parse('next saturday')->toDateTimeString());
        $this->assertSame('2013-09-08 00:00:00', $class::parse('next sunday')->toDateTimeString());

        $this->assertSame('2013-08-26 00:00:00', $class::parse('last monday')->toDateTimeString());
        $this->assertSame('2013-08-27 00:00:00', $class::parse('last tuesday')->toDateTimeString());
        $this->assertSame('2013-08-28 00:00:00', $class::parse('last wednesday')->toDateTimeString());
        $this->assertSame('2013-08-29 00:00:00', $class::parse('last thursday')->toDateTimeString());
        $this->assertSame('2013-08-30 00:00:00', $class::parse('last friday')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', $class::parse('last saturday')->toDateTimeString());
        $this->assertSame('2013-08-25 00:00:00', $class::parse('last sunday')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', $class::parse('this monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', $class::parse('this tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', $class::parse('this wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', $class::parse('this thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', $class::parse('this friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', $class::parse('this saturday')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', $class::parse('this sunday')->toDateTimeString());

        $this->assertSame('2013-10-01 05:15:05', $class::parse('first day of next month')->toDateTimeString());
        $this->assertSame('2013-09-30 05:15:05', $class::parse('last day of this month')->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseRelativeWithMinusSignsInDate($class)
    {
        $notNow = $class::parse('2013-09-01 05:15:05');
        $class::setTestNow($notNow);

        $this->assertSame('2000-01-03 00:00:00', $class::parse('2000-1-3')->toDateTimeString());
        $this->assertSame('2000-10-10 00:00:00', $class::parse('2000-10-10')->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithTimeZone($class)
    {
        $notNow = $class::parse('2013-07-01 12:00:00', 'America/New_York');
        $class::setTestNow($notNow);

        $this->assertSame('2013-07-01T12:00:00-04:00', $class::parse('now')->toIso8601String());
        $this->assertSame('2013-07-01T11:00:00-05:00', $class::parse('now', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01T09:00:00-07:00', $class::parse('now', 'America/Vancouver')->toIso8601String());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseRelativeWithTimeZone($class)
    {
        $notNow = $class::parse('2013-07-01 12:00:00', 'America/New_York');
        $class::setTestNow($notNow);

        $this->assertSame('2013-07-01T10:55:00-05:00', $class::parse('5 minutes ago', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01 10:55:00', $class::parse('5 minutes ago', 'America/Mexico_City')->toDateTimeString());
    }

    /**
     * Test parse() with relative values and timezones
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseRelativeWithTimezoneAndTestValueSet($class)
    {
        $notNow = $class::parse('2013-07-01 12:00:00', 'America/New_York');
        $class::setTestNow($notNow);

        $this->assertSame('06:30:00', $class::parse('2013-07-01 06:30:00', 'America/Mexico_City')->toTimeString());
        $this->assertSame('06:30:00', $class::parse('6:30', 'America/Mexico_City')->toTimeString());

        $this->assertSame('2013-07-01T06:30:00-04:00', $class::parse('2013-07-01 06:30:00')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', $class::parse('2013-07-01 06:30:00', 'America/Mexico_City')->toIso8601String());

        $this->assertSame('2013-07-01T06:30:00-04:00', $class::parse('06:30')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-04:00', $class::parse('6:30')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', $class::parse('6:30', 'America/Mexico_City')->toIso8601String());

        $this->assertSame('2013-07-01T06:30:00-05:00', $class::parse('6:30:00', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', $class::parse('06:30:00', 'America/Mexico_City')->toIso8601String());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNullTimezone($class)
    {
        $c = new $class('2016-01-01 00:00:00', 'Europe/Copenhagen');
        $class::setTestNow($c);

        $result = new $class('now', null);
        $this->assertEquals(new DateTimeZone('America/Toronto'), $result->tz);
        $this->assertEquals('2015-12-31 18:00:00', $result->format('Y-m-d H:i:s'));
        $this->assertEquals(new DateTimeZone('Europe/Copenhagen'), $class::getTestNow()->tz);
    }

    /**
     * Test that setting testNow() on one class sets it on all of the chronos classes.
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSetTestNowSingular($class)
    {
        $c = new $class('2016-01-03 00:00:00', 'Europe/Copenhagen');
        $class::setTestNow($c);

        $this->assertEquals($c, MutableDate::getTestNow());
        $this->assertEquals($c, Date::getTestNow());
        $this->assertEquals($c, Chronos::getTestNow());
        $this->assertEquals($c, MutableDateTime::getTestNow());
    }
}
