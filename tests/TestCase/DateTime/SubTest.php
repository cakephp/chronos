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
use Cake\Chronos\Test\TestCase\TestCase;

class SubTest extends TestCase
{
    public function testSubYearsPositive()
    {
        $this->assertSame(1974, Chronos::createFromDate(1975)->subYears(1)->year);
    }

    public function testSubYearsZero()
    {
        $this->assertSame(1975, Chronos::createFromDate(1975)->subYears(0)->year);
    }

    public function testSubYearsNegative()
    {
        $this->assertSame(1976, Chronos::createFromDate(1975)->subYears(-1)->year);
    }

    public function testSubYearNoOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2013, 2, 28)->subYears(1);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    public function testSubYearsWithOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2014, 2, 29)->subYearsWithOverflow(2);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    public function testSubYearWithOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2013, 2, 28)->subYearsWithOverflow(1);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    public function testSubMonthsPositive()
    {
        $this->assertSame(12, Chronos::createFromDate(1975, 1, 1)->subMonths(1)->month);
    }

    public function testSubMonthsZero()
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 1, 1)->subMonths(0)->month);
    }

    public function testSubMonthsNegative()
    {
        $this->assertSame(2, Chronos::createFromDate(1975, 1, 1)->subMonths(-1)->month);
    }

    public function testSubMonth()
    {
        $this->assertSame(12, Chronos::createFromDate(1975, 1, 1)->subMonths(1)->month);
    }

    public function testSubDaysPositive()
    {
        $this->assertSame(30, Chronos::createFromDate(1975, 5, 1)->subDays(1)->day);
    }

    public function testSubDaysZero()
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 5, 1)->subDays(0)->day);
    }

    public function testSubDaysNegative()
    {
        $this->assertSame(2, Chronos::createFromDate(1975, 5, 1)->subDays(-1)->day);
    }

    public function testSubDay()
    {
        $this->assertSame(30, Chronos::createFromDate(1975, 5, 1)->subDays(1)->day);
    }

    public function testSubWeekdayDuringWeekend()
    {
        $this->assertSame(6, Chronos::createFromDate(2012, 1, 8)->subWeekdays(1)->day);
    }

    public function testSubWeekdaysPositive()
    {
        $dt = Chronos::create(2012, 1, 4, 13, 2, 1)->subWeekdays(9);
        $this->assertSame(22, $dt->day);

        // Test for https://bugs.php.net/bug.php?id=54909
        $this->assertSame(13, $dt->hour);
        $this->assertSame(2, $dt->minute);
        $this->assertSame(1, $dt->second);
    }

    public function testSubWeekdaysZero()
    {
        $this->assertSame(4, Chronos::createFromDate(2012, 1, 4)->subWeekdays(0)->day);
    }

    public function testSubWeekdaysNegative()
    {
        $this->assertSame(13, Chronos::createFromDate(2012, 1, 31)->subWeekdays(-9)->day);
    }

    public function testSubWeekday()
    {
        $this->assertSame(6, Chronos::createFromDate(2012, 1, 9)->subWeekdays(1)->day);
    }

    public function testSubWeeksPositive()
    {
        $this->assertSame(14, Chronos::createFromDate(1975, 5, 21)->subWeeks(1)->day);
    }

    public function testSubWeeksZero()
    {
        $this->assertSame(21, Chronos::createFromDate(1975, 5, 21)->subWeeks(0)->day);
    }

    public function testSubWeeksNegative()
    {
        $this->assertSame(28, Chronos::createFromDate(1975, 5, 21)->subWeeks(-1)->day);
    }

    public function testSubWeek()
    {
        $this->assertSame(14, Chronos::createFromDate(1975, 5, 21)->subWeeks(1)->day);
    }

    public function testSubHoursPositive()
    {
        $this->assertSame(23, Chronos::createFromTime(0)->subHours(1)->hour);
    }

    public function testSubHoursZero()
    {
        $this->assertSame(0, Chronos::createFromTime(0)->subHours(0)->hour);
    }

    public function testSubHoursNegative()
    {
        $this->assertSame(1, Chronos::createFromTime(0)->subHours(-1)->hour);
    }

    public function testSubHour()
    {
        $this->assertSame(23, Chronos::createFromTime(0)->subHours(1)->hour);
    }

    public function testSubMinutesPositive()
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0)->subMinutes(1)->minute);
    }

    public function testSubMinutesZero()
    {
        $this->assertSame(0, Chronos::createFromTime(0, 0)->subMinutes(0)->minute);
    }

    public function testSubMinutesNegative()
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0)->subMinutes(-1)->minute);
    }

    public function testSubMinute()
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0)->subMinutes(1)->minute);
    }

    public function testSubSecondsPositive()
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0, 0)->subSeconds(1)->second);
    }

    public function testSubSecondsZero()
    {
        $this->assertSame(0, Chronos::createFromTime(0, 0, 0)->subSeconds(0)->second);
    }

    public function testSubSecondsNegative()
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0, 0)->subSeconds(-1)->second);
    }

    public function testSubSecond()
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0, 0)->subSeconds(1)->second);
    }

    /***** Test non plural methods with non default args *****/

    public function testSubYearPassingArg()
    {
        $this->assertSame(1973, Chronos::createFromDate(1975)->subYears(2)->year);
    }

    public function testSubMonthPassingArg()
    {
        $this->assertSame(3, Chronos::createFromDate(1975, 5, 1)->subMonths(2)->month);
    }

    public function testSubMonthNoOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2011, 4, 30)->subMonths(2);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    public function testSubMonthsWithOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2011, 4, 30)->subMonthsWithOverflow(2);
        $this->assertSame(3, $dt->month);
        $this->assertSame(2, $dt->day);
    }

    public function testSubMonthWithOverflowPassingArg()
    {
        $dt = Chronos::createFromDate(2011, 3, 30)->subMonthsWithOverflow(1);
        $this->assertSame(3, $dt->month);
        $this->assertSame(2, $dt->day);
    }

    public function testSubDayPassingArg()
    {
        $this->assertSame(8, Chronos::createFromDate(1975, 5, 10)->subDays(2)->day);
    }

    public function testSubHourPassingArg()
    {
        $this->assertSame(22, Chronos::createFromTime(0)->subHours(2)->hour);
    }

    public function testSubMinutePassingArg()
    {
        $this->assertSame(58, Chronos::createFromTime(0)->subMinutes(2)->minute);
    }

    public function testSubSecondPassingArg()
    {
        $this->assertSame(58, Chronos::createFromTime(0)->subSeconds(2)->second);
    }
}
