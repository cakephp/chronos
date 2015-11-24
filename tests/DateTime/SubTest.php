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

class SubTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubYearsPositive($class)
    {
        $this->assertSame(1974, $class::createFromDate(1975)->subYears(1)->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubYearsZero($class)
    {
        $this->assertSame(1975, $class::createFromDate(1975)->subYears(0)->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubYearsNegative($class)
    {
        $this->assertSame(1976, $class::createFromDate(1975)->subYears(-1)->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubYear($class)
    {
        $this->assertSame(1974, $class::createFromDate(1975)->subYear()->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthsPositive($class)
    {
        $this->assertSame(12, $class::createFromDate(1975, 1, 1)->subMonths(1)->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthsZero($class)
    {
        $this->assertSame(1, $class::createFromDate(1975, 1, 1)->subMonths(0)->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthsNegative($class)
    {
        $this->assertSame(2, $class::createFromDate(1975, 1, 1)->subMonths(-1)->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonth($class)
    {
        $this->assertSame(12, $class::createFromDate(1975, 1, 1)->subMonth()->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubDaysPositive($class)
    {
        $this->assertSame(30, $class::createFromDate(1975, 5, 1)->subDays(1)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubDaysZero($class)
    {
        $this->assertSame(1, $class::createFromDate(1975, 5, 1)->subDays(0)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubDaysNegative($class)
    {
        $this->assertSame(2, $class::createFromDate(1975, 5, 1)->subDays(-1)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubDay($class)
    {
        $this->assertSame(30, $class::createFromDate(1975, 5, 1)->subDay()->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeekdayDuringWeekend($class)
    {
        $this->assertSame(6, $class::createFromDate(2012, 1, 8)->subWeekday()->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeekdaysPositive($class)
    {
        $dt = $class::create(2012, 1, 4, 13, 2, 1)->subWeekdays(9);
        $this->assertSame(22, $dt->day);

        // Test for https://bugs.php.net/bug.php?id=54909
        $this->assertSame(13, $dt->hour);
        $this->assertSame(2, $dt->minute);
        $this->assertSame(1, $dt->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeekdaysZero($class)
    {
        $this->assertSame(4, $class::createFromDate(2012, 1, 4)->subWeekdays(0)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeekdaysNegative($class)
    {
        $this->assertSame(13, $class::createFromDate(2012, 1, 31)->subWeekdays(-9)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeekday($class)
    {
        $this->assertSame(6, $class::createFromDate(2012, 1, 9)->subWeekday()->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeeksPositive($class)
    {
        $this->assertSame(14, $class::createFromDate(1975, 5, 21)->subWeeks(1)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeeksZero($class)
    {
        $this->assertSame(21, $class::createFromDate(1975, 5, 21)->subWeeks(0)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeeksNegative($class)
    {
        $this->assertSame(28, $class::createFromDate(1975, 5, 21)->subWeeks(-1)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubWeek($class)
    {
        $this->assertSame(14, $class::createFromDate(1975, 5, 21)->subWeek()->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubHoursPositive($class)
    {
        $this->assertSame(23, $class::createFromTime(0)->subHours(1)->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubHoursZero($class)
    {
        $this->assertSame(0, $class::createFromTime(0)->subHours(0)->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubHoursNegative($class)
    {
        $this->assertSame(1, $class::createFromTime(0)->subHours(-1)->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubHour($class)
    {
        $this->assertSame(23, $class::createFromTime(0)->subHour()->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMinutesPositive($class)
    {
        $this->assertSame(59, $class::createFromTime(0, 0)->subMinutes(1)->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMinutesZero($class)
    {
        $this->assertSame(0, $class::createFromTime(0, 0)->subMinutes(0)->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMinutesNegative($class)
    {
        $this->assertSame(1, $class::createFromTime(0, 0)->subMinutes(-1)->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMinute($class)
    {
        $this->assertSame(59, $class::createFromTime(0, 0)->subMinute()->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubSecondsPositive($class)
    {
        $this->assertSame(59, $class::createFromTime(0, 0, 0)->subSeconds(1)->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubSecondsZero($class)
    {
        $this->assertSame(0, $class::createFromTime(0, 0, 0)->subSeconds(0)->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubSecondsNegative($class)
    {
        $this->assertSame(1, $class::createFromTime(0, 0, 0)->subSeconds(-1)->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubSecond($class)
    {
        $this->assertSame(59, $class::createFromTime(0, 0, 0)->subSecond()->second);
    }

    /***** Test non plural methods with non default args *****/

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubYearPassingArg($class)
    {
        $this->assertSame(1973, $class::createFromDate(1975)->subYear(2)->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthPassingArg($class)
    {
        $this->assertSame(3, $class::createFromDate(1975, 5, 1)->subMonth(2)->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthNoOverflowPassingArg($class)
    {
        $dt = $class::createFromDate(2011, 4, 30)->subMonths(2);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthsWithOverflowPassingArg($class)
    {
        $dt = $class::createFromDate(2011, 4, 30)->subMonthsWithOverflow(2);
        $this->assertSame(3, $dt->month);
        $this->assertSame(2, $dt->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMonthWithOverflowPassingArg($class)
    {
        $dt = $class::createFromDate(2011, 3, 30)->subMonthWithOverflow();
        $this->assertSame(3, $dt->month);
        $this->assertSame(2, $dt->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubDayPassingArg($class)
    {
        $this->assertSame(8, $class::createFromDate(1975, 5, 10)->subDay(2)->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubHourPassingArg($class)
    {
        $this->assertSame(22, $class::createFromTime(0)->subHour(2)->hour);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubMinutePassingArg($class)
    {
        $this->assertSame(58, $class::createFromTime(0)->subMinute(2)->minute);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSubSecondPassingArg($class)
    {
        $this->assertSame(58, $class::createFromTime(0)->subSecond(2)->second);
    }
}
