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

use Cake\Chronos\ChronosInterface;
use TestCase;

class GettersTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testGettersThrowExceptionOnUnknownGetter($class)
    {
        $class::create(1234, 5, 6, 7, 8, 9)->sdfsdfss;
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testYearGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(1234, $d->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testYearIsoGetter($class)
    {
        $d = $class::createFromDate(2012, 12, 31);
        $this->assertSame(2013, $d->yearIso);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMonthGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(5, $d->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDayGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(6, $d->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testHourGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(7, $d->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinuteGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(8, $d->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondGetter($class)
    {
        $d = $class::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(9, $d->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMicroGetter($class)
    {
        $micro = 345678;
        $d = $class::parse('2014-01-05 12:34:11.' . $micro);
        $this->assertSame($micro, $d->micro);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDayOfWeekGetter($class)
    {
        $d = $class::create(2012, 5, 7, 7, 8, 9);
        $this->assertSame($class::MONDAY, $d->dayOfWeek);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDayOfYearGetter($class)
    {
        $d = $class::createFromDate(2012, 5, 7);
        $this->assertSame(127, $d->dayOfYear);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDaysInMonthGetter($class)
    {
        $d = $class::createFromDate(2012, 5, 7);
        $this->assertSame(31, $d->daysInMonth);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTimestampGetter($class)
    {
        $d = $class::create();
        $d = $d->setTimezone('GMT')->setDateTime(1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetAge($class)
    {
        $d = $class::now();
        $this->assertSame(0, $d->age);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetAgeWithRealAge($class)
    {
        $d = $class::createFromDate(1975, 5, 21);
        $age = intval(substr((int)date('Ymd') - (int)date('Ymd', $d->timestamp), 0, -4));

        $this->assertSame($age, $d->age);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterFirst($class)
    {
        $d = $class::createFromDate(2012, 1, 1);
        $this->assertSame(1, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterFirstEnd($class)
    {
        $d = $class::createFromDate(2012, 3, 31);
        $this->assertSame(1, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterSecond($class)
    {
        $d = $class::createFromDate(2012, 4, 1);
        $this->assertSame(2, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterThird($class)
    {
        $d = $class::createFromDate(2012, 7, 1);
        $this->assertSame(3, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterFourth($class)
    {
        $d = $class::createFromDate(2012, 10, 1);
        $this->assertSame(4, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetQuarterFirstLast($class)
    {
        $d = $class::createFromDate(2012, 12, 31);
        $this->assertSame(4, $d->quarter);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetLocalTrue($class)
    {
        // Default timezone has been set to America/Toronto in TestCase.php
        // @see : http://en.wikipedia.org/wiki/List_of_UTC_time_offsets
        $this->assertTrue($class::createFromDate(2012, 1, 1, 'America/Toronto')->local);
        $this->assertTrue($class::createFromDate(2012, 1, 1, 'America/New_York')->local);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetLocalFalse($class)
    {
        $this->assertFalse($class::createFromDate(2012, 7, 1, 'UTC')->local);
        $this->assertFalse($class::createFromDate(2012, 7, 1, 'Europe/London')->local);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetUtcFalse($class)
    {
        $this->assertFalse($class::createFromDate(2013, 1, 1, 'America/Toronto')->utc);
        $this->assertFalse($class::createFromDate(2013, 1, 1, 'Europe/Paris')->utc);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetUtcTrue($class)
    {
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Atlantic/Reykjavik')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Europe/Lisbon')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Africa/Casablanca')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Africa/Dakar')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Europe/Dublin')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'Europe/London')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'UTC')->utc);
        $this->assertTrue($class::createFromDate(2013, 1, 1, 'GMT')->utc);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetDstFalse($class)
    {
        $this->assertFalse($class::createFromDate(2012, 1, 1, 'America/Toronto')->dst);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetDstTrue($class)
    {
        $this->assertTrue($class::createFromDate(2012, 7, 1, 'America/Toronto')->dst);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetForTorontoWithDST($class)
    {
        $this->assertSame(-18000, $class::createFromDate(2012, 1, 1, 'America/Toronto')->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetForTorontoNoDST($class)
    {
        $this->assertSame(-14400, $class::createFromDate(2012, 6, 1, 'America/Toronto')->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetForGMT($class)
    {
        $this->assertSame(0, $class::createFromDate(2012, 6, 1, 'GMT')->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetHoursForTorontoWithDST($class)
    {
        $this->assertSame(-5, $class::createFromDate(2012, 1, 1, 'America/Toronto')->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetHoursForTorontoNoDST($class)
    {
        $this->assertSame(-4, $class::createFromDate(2012, 6, 1, 'America/Toronto')->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testOffsetHoursForGMT($class)
    {
        $this->assertSame(0, $class::createFromDate(2012, 6, 1, 'GMT')->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLeapYearTrue($class)
    {
        $this->assertTrue($class::createFromDate(2012, 1, 1)->isLeapYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLeapYearFalse($class)
    {
        $this->assertFalse($class::createFromDate(2011, 1, 1)->isLeapYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWeekOfMonth($class)
    {
        $this->assertSame(5, $class::createFromDate(2012, 9, 30)->weekOfMonth);
        $this->assertSame(4, $class::createFromDate(2012, 9, 28)->weekOfMonth);
        $this->assertSame(3, $class::createFromDate(2012, 9, 20)->weekOfMonth);
        $this->assertSame(2, $class::createFromDate(2012, 9, 8)->weekOfMonth);
        $this->assertSame(1, $class::createFromDate(2012, 9, 1)->weekOfMonth);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWeekOfYearFirstWeek($class)
    {
        $this->assertSame(52, $class::createFromDate(2012, 1, 1)->weekOfYear);
        $this->assertSame(1, $class::createFromDate(2012, 1, 2)->weekOfYear);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWeekOfYearLastWeek($class)
    {
        $this->assertSame(52, $class::createFromDate(2012, 12, 30)->weekOfYear);
        $this->assertSame(1, $class::createFromDate(2012, 12, 31)->weekOfYear);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetWeekStartsAt($class)
    {
        $d = $class::createFromDate(2012, 12, 31);
        $this->assertSame(ChronosInterface::MONDAY, $d->getWeekStartsAt());

        $d::setWeekStartsAt(ChronosInterface::SUNDAY);
        $this->assertSame(ChronosInterface::SUNDAY, $d->getWeekStartsAt());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetWeekEndsAt($class)
    {
        $d = $class::createFromDate(2012, 12, 31);
        $this->assertSame(ChronosInterface::SUNDAY, $d->getWeekEndsAt());

        $d::setWeekEndsAt(ChronosInterface::SATURDAY);
        $this->assertSame(ChronosInterface::SATURDAY, $d->getWeekEndsAt());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetTimezone($class)
    {
        $dt = $class::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->timezone->getName());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetTz($class)
    {
        $dt = $class::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->tz->getName());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetTimezoneName($class)
    {
        $dt = $class::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->timezoneName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetTzName($class)
    {
        $dt = $class::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testInvalidGetter($class)
    {
        $d = $class::now();
        $bb = $d->doesNotExit;
    }
}
