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
use Cake\Chronos\Test\TestCase\TestCase;
use DateTimeZone;

class TestingAidsTest extends TestCase
{
    public function testTestingAidsWithTestNowNotSet()
    {
        Chronos::setTestNow();

        $this->assertFalse(Chronos::hasTestNow());
        $this->assertNull(Chronos::getTestNow());
    }

    public function testTestingAidsWithTestNowSet()
    {
        $notNow = Chronos::yesterday();
        Chronos::setTestNow($notNow);

        $this->assertTrue(Chronos::hasTestNow());
        $this->assertSame($notNow, Chronos::getTestNow());
    }

    public function testTestingAidsWithTestNowSetToString()
    {
        Chronos::setTestNow('2016-11-23');
        $this->assertTrue(Chronos::hasTestNow());
        $this->assertSame((string)Chronos::getTestNow(), (string)Chronos::parse('2016-11-23'));
    }

    public function testConstructorWithTestValueSet()
    {
        $notNow = Chronos::yesterday();
        Chronos::setTestNow($notNow);

        $this->assertSame((string)$notNow, (string)new Chronos());
        $this->assertSame((string)$notNow, (string)new Chronos(null));
        $this->assertSame((string)$notNow, (string)new Chronos(''));
        $this->assertSame((string)$notNow, (string)new Chronos('now'));
    }

    public function testNowWithTestValueSet()
    {
        $notNow = Chronos::yesterday();
        Chronos::setTestNow($notNow);

        $this->assertSame((string)$notNow, (string)Chronos::now());
    }

    /**
     * Ensure that using test now doesn't mutate test now.
     */
    public function testNowNoMutateDateTime()
    {
        $value = '2018-06-21 10:11:12';
        $notNow = new Chronos($value);
        Chronos::setTestNow($notNow);

        $instance = new Chronos('-10 minutes');
        $this->assertSame('10:01:12', $instance->format('H:i:s'));

        $instance = new Chronos('-10 minutes');
        $this->assertSame('10:01:12', $instance->format('H:i:s'));
    }

    /**
     * Ensure that using test now doesn't mutate test now.
     */
    public function testNowNoMutateDate()
    {
        $value = '2018-06-21 10:11:12';
        $notNow = new Chronos($value);
        Chronos::setTestNow($notNow);

        $instance = new ChronosDate('-1 day');
        $this->assertSame('2018-06-20 00:00:00', $instance->format('Y-m-d H:i:s'));

        $instance = new ChronosDate('-1 day');
        $this->assertSame('2018-06-20 00:00:00', $instance->format('Y-m-d H:i:s'));

        $instance = new ChronosDate('-23 hours');
        $this->assertSame('2018-06-20 00:00:00', $instance->format('Y-m-d H:i:s'));
    }

    public function testParseWithTestValueSet()
    {
        $notNow = Chronos::yesterday();
        Chronos::setTestNow($notNow);

        $this->assertSame((string)$notNow, (string)Chronos::parse());
        $this->assertSame((string)$notNow, (string)Chronos::parse(null));
        $this->assertSame((string)$notNow, (string)Chronos::parse(''));
        $this->assertSame((string)$notNow, (string)Chronos::parse('now'));
    }

    public function testParseRelativeWithTestValueSet()
    {
        $notNow = Chronos::parse('2013-09-01 05:15:05');
        Chronos::setTestNow($notNow);

        $this->assertSame('2013-09-01 06:30:00', Chronos::parse('6:30')->toDateTimeString());
        $this->assertSame('2013-09-01 06:30:00', Chronos::parse('6:30:00')->toDateTimeString());
        $this->assertSame('2013-09-01 06:30:00', Chronos::parse('06:30:00')->toDateTimeString());

        $this->assertSame('2013-09-01 05:10:05', Chronos::parse('5 minutes ago')->toDateTimeString());

        $this->assertSame('2013-08-25 05:15:05', Chronos::parse('1 week ago')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Chronos::parse('tomorrow')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', Chronos::parse('today')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', Chronos::parse('midnight')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', Chronos::parse('yesterday')->toDateTimeString());

        $this->assertSame('2013-09-02 05:15:05', Chronos::parse('+1 day')->toDateTimeString());
        $this->assertSame('2013-08-31 05:15:05', Chronos::parse('-1 day')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Chronos::parse('next monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', Chronos::parse('next tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', Chronos::parse('next wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', Chronos::parse('next thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', Chronos::parse('next friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', Chronos::parse('next saturday')->toDateTimeString());
        $this->assertSame('2013-09-08 00:00:00', Chronos::parse('next sunday')->toDateTimeString());

        $this->assertSame('2013-08-26 00:00:00', Chronos::parse('last monday')->toDateTimeString());
        $this->assertSame('2013-08-27 00:00:00', Chronos::parse('last tuesday')->toDateTimeString());
        $this->assertSame('2013-08-28 00:00:00', Chronos::parse('last wednesday')->toDateTimeString());
        $this->assertSame('2013-08-29 00:00:00', Chronos::parse('last thursday')->toDateTimeString());
        $this->assertSame('2013-08-30 00:00:00', Chronos::parse('last friday')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', Chronos::parse('last saturday')->toDateTimeString());
        $this->assertSame('2013-08-25 00:00:00', Chronos::parse('last sunday')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Chronos::parse('this monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', Chronos::parse('this tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', Chronos::parse('this wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', Chronos::parse('this thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', Chronos::parse('this friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', Chronos::parse('this saturday')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', Chronos::parse('this sunday')->toDateTimeString());

        $this->assertSame('2013-10-01 05:15:05', Chronos::parse('first day of next month')->toDateTimeString());
        $this->assertSame('2013-09-30 05:15:05', Chronos::parse('last day of this month')->toDateTimeString());
    }

    public function testParseRelativeWithMinusSignsInDate()
    {
        $notNow = Chronos::parse('2013-09-01 05:15:05');
        Chronos::setTestNow($notNow);

        $this->assertSame('2000-01-03 00:00:00', Chronos::parse('2000-1-3')->toDateTimeString());
        $this->assertSame('2000-10-10 00:00:00', Chronos::parse('2000-10-10')->toDateTimeString());
    }

    public function testParseWithTimeZone()
    {
        $notNow = Chronos::parse('2013-07-01 12:00:00', 'America/New_York');
        Chronos::setTestNow($notNow);

        $this->assertSame('2013-07-01T12:00:00-04:00', Chronos::parse('now')->toIso8601String());
        $this->assertSame('2013-07-01T11:00:00-05:00', Chronos::parse('now', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01T09:00:00-07:00', Chronos::parse('now', 'America/Vancouver')->toIso8601String());
    }

    public function testParseRelativeWithTimeZone()
    {
        $notNow = Chronos::parse('2013-07-01 12:00:00', 'America/New_York');
        Chronos::setTestNow($notNow);

        $this->assertSame('2013-07-01T10:55:00-05:00', Chronos::parse('5 minutes ago', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01 10:55:00', Chronos::parse('5 minutes ago', 'America/Mexico_City')->toDateTimeString());
    }

    /**
     * Test parse() with relative values and timezones
     */
    public function testParseRelativeWithTimezoneAndTestValueSet()
    {
        $notNow = Chronos::parse('2013-07-01 12:00:00', 'America/New_York');
        Chronos::setTestNow($notNow);

        $this->assertSame('06:30:00', Chronos::parse('2013-07-01 06:30:00', 'America/Mexico_City')->toTimeString());
        $this->assertSame('06:30:00', Chronos::parse('6:30', 'America/Mexico_City')->toTimeString());

        $this->assertSame('2013-07-01T06:30:00-04:00', Chronos::parse('2013-07-01 06:30:00')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', Chronos::parse('2013-07-01 06:30:00', 'America/Mexico_City')->toIso8601String());

        $this->assertSame('2013-07-01T06:30:00-04:00', Chronos::parse('06:30')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-04:00', Chronos::parse('6:30')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', Chronos::parse('6:30', 'America/Mexico_City')->toIso8601String());

        $this->assertSame('2013-07-01T06:30:00-05:00', Chronos::parse('6:30:00', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01T06:30:00-05:00', Chronos::parse('06:30:00', 'America/Mexico_City')->toIso8601String());
    }

    public function testNullTimezone()
    {
        $c = new Chronos('2016-01-01 00:00:00', 'Europe/Copenhagen');
        Chronos::setTestNow($c);

        $result = new Chronos('now', null);
        $this->assertSame((new DateTimeZone('America/Toronto'))->getName(), $result->tz->getName());
        $this->assertSame('2015-12-31 18:00:00', $result->format('Y-m-d H:i:s'));
        $this->assertSame((new DateTimeZone('Europe/Copenhagen'))->getName(), Chronos::getTestNow()->tz->getName());
    }

    /**
     * Test that setting testNow() on one class sets it on all of the chronos classes.
     */
    public function testSetTestNowSingular()
    {
        $c = new Chronos('2016-01-03 00:00:00', 'Europe/Copenhagen');
        Chronos::setTestNow($c);

        $this->assertSame($c, Chronos::getTestNow());
    }
}
