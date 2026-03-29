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

class AddTest extends TestCase
{
    public function testAddYearsPositive(): void
    {
        $this->assertSame(1976, Chronos::createFromDate(1975)->addYears(1)->year);
    }

    public function testAddYearsZero(): void
    {
        $this->assertSame(1975, Chronos::createFromDate(1975)->addYears(0)->year);
    }

    public function testAddYearsNegative(): void
    {
        $this->assertSame(1974, Chronos::createFromDate(1975)->addYears(-1)->year);
    }

    public function testAddYears(): void
    {
        $this->assertSame(1976, Chronos::createFromDate(1975)->addYears(1)->year);
    }

    public function testAddMonthsPositive(): void
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 12)->addMonths(1)->month);
    }

    public function testAddMonthsZero(): void
    {
        $this->assertSame(12, Chronos::createFromDate(1975, 12)->addMonths(0)->month);
    }

    public function testAddMonthsNegative(): void
    {
        $this->assertSame(11, Chronos::createFromDate(1975, 12, 1)->addMonths(-1)->month);
    }

    public function testAddMonth(): void
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 12)->addMonths(1)->month);
    }

    public function testAddMonthWithOverflow(): void
    {
        $this->assertSame(3, Chronos::createFromDate(2012, 1, 31)->addMonthsWithOverflow(1)->month);
    }

    public function testAddMonthsNoOverflowPositive(): void
    {
        $this->assertSame('2012-02-29', Chronos::createFromDate(2012, 1, 31)->addMonths(1)->toDateString());
        $this->assertSame('2012-03-31', Chronos::createFromDate(2012, 1, 31)->addMonths(2)->toDateString());
        $this->assertSame('2012-03-29', Chronos::createFromDate(2012, 2, 29)->addMonths(1)->toDateString());
        $this->assertSame('2012-02-29', Chronos::createFromDate(2011, 12, 31)->addMonths(2)->toDateString());
    }

    public function testAddMonthsNoOverflowZero(): void
    {
        $this->assertSame(12, Chronos::createFromDate(1975, 12)->addMonths(0)->month);
    }

    public function testAddMonthsNoOverflowNegative(): void
    {
        $this->assertSame('2012-01-29', Chronos::createFromDate(2012, 2, 29)->addMonths(-1)->toDateString());
        $this->assertSame('2012-01-31', Chronos::createFromDate(2012, 3, 31)->addMonths(-2)->toDateString());
        $this->assertSame('2012-02-29', Chronos::createFromDate(2012, 3, 31)->addMonths(-1)->toDateString());
        $this->assertSame('2011-12-31', Chronos::createFromDate(2012, 1, 31)->addMonths(-1)->toDateString());
    }

    public function testAddDaysPositive(): void
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 5, 31)->addDays(1)->day);
    }

    public function testAddDaysZero(): void
    {
        $this->assertSame(31, Chronos::createFromDate(1975, 5, 31)->addDays(0)->day);
    }

    public function testAddDaysNegative(): void
    {
        $this->assertSame(30, Chronos::createFromDate(1975, 5, 31)->addDays(-1)->day);
    }

    public function testAddDay(): void
    {
        $this->assertSame(1, Chronos::createFromDate(1975, 5, 31)->addDays(1)->day);
    }

    public function testAddWeekdayDuringWeekend(): void
    {
        $this->assertSame(9, Chronos::createFromDate(2012, 1, 7)->addWeekdays(1)->day);
    }

    public function testAddWeekdaysPositive(): void
    {
        $dt = Chronos::create(2012, 1, 4, 13, 2, 1)->addWeekdays(9);
        $this->assertSame(17, $dt->day);

        // Test for https://bugs.php.net/bug.php?id=54909
        $this->assertSame(13, $dt->hour);
        $this->assertSame(2, $dt->minute);
        $this->assertSame(1, $dt->second);
    }

    public function testAddWeekdaysZero(): void
    {
        $this->assertSame(4, Chronos::createFromDate(2012, 1, 4)->addWeekdays(0)->day);
    }

    public function testAddWeekdaysNegative(): void
    {
        $this->assertSame(18, Chronos::createFromDate(2012, 1, 31)->addWeekdays(-9)->day);
    }

    public function testAddWeekday(): void
    {
        $this->assertSame(9, Chronos::createFromDate(2012, 1, 6)->addWeekdays(1)->day);
    }

    public function testAddWeeksPositive(): void
    {
        $this->assertSame(28, Chronos::createFromDate(1975, 5, 21)->addWeeks(1)->day);
    }

    public function testAddWeeksZero(): void
    {
        $this->assertSame(21, Chronos::createFromDate(1975, 5, 21)->addWeeks(0)->day);
    }

    public function testAddWeeksNegative(): void
    {
        $this->assertSame(14, Chronos::createFromDate(1975, 5, 21)->addWeeks(-1)->day);
    }

    public function testAddWeek(): void
    {
        $this->assertSame(28, Chronos::createFromDate(1975, 5, 21)->addWeeks(1)->day);
    }

    public function testAddHoursPositive(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0)->addHours(1)->hour);
    }

    public function testAddHoursZero(): void
    {
        $this->assertSame(0, Chronos::createFromTime(0)->addHours(0)->hour);
    }

    public function testAddHoursNegative(): void
    {
        $this->assertSame(23, Chronos::createFromTime(0)->addHours(-1)->hour);
    }

    public function testAddHour(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0)->addHours(1)->hour);
    }

    public function testAddMinutesPositive(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0)->addMinutes(1)->minute);
    }

    public function testAddMinutesZero(): void
    {
        $this->assertSame(0, Chronos::createFromTime(0, 0)->addMinutes(0)->minute);
    }

    public function testAddMinutesNegative(): void
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0)->addMinutes(-1)->minute);
    }

    public function testAddMinute(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0)->addMinutes(1)->minute);
    }

    public function testAddSecondsPositive(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0, 0)->addSeconds(1)->second);
    }

    public function testAddSecondsZero(): void
    {
        $this->assertSame(0, Chronos::createFromTime(0, 0, 0)->addSeconds(0)->second);
    }

    public function testAddSecondsNegative(): void
    {
        $this->assertSame(59, Chronos::createFromTime(0, 0, 0)->addSeconds(-1)->second);
    }

    public function testAddSecond(): void
    {
        $this->assertSame(1, Chronos::createFromTime(0, 0, 0)->addSeconds(1)->second);
    }

    /***** Test non plural methods with non default args *****/

    public function testAddYearPassingArg(): void
    {
        $this->assertSame(1977, Chronos::createFromDate(1975)->addYears(2)->year);
    }

    public function testAddYearWithOverflow(): void
    {
        $this->assertSame('2013-03-01', Chronos::createFromDate(2012, 2, 29)->addYearsWithOverflow(1)->toDateString());
    }

    public function testAddYearsNoOverflowPositive(): void
    {
        $this->assertSame('2013-01-31', Chronos::createFromDate(2012, 1, 31)->addYears(1)->toDateString());
        $this->assertSame('2014-01-31', Chronos::createFromDate(2012, 1, 31)->addYears(2)->toDateString());
        $this->assertSame('2013-02-28', Chronos::createFromDate(2012, 2, 29)->addYears(1)->toDateString());
        $this->assertSame('2013-12-31', Chronos::createFromDate(2011, 12, 31)->addYears(2)->toDateString());
    }

    public function testAddYearsNoOverflowZero(): void
    {
        $this->assertSame('1975-12-31', Chronos::createFromDate(1975, 12, 31)->addYears(0)->toDateString());
    }

    public function testAddYearsNoOverflowNegative(): void
    {
        $this->assertSame('2011-02-28', Chronos::createFromDate(2012, 2, 29)->addYears(-1)->toDateString());
        $this->assertSame('2010-03-31', Chronos::createFromDate(2012, 3, 31)->addYears(-2)->toDateString());
        $this->assertSame('2011-03-31', Chronos::createFromDate(2012, 3, 31)->addYears(-1)->toDateString());
        $this->assertSame('2011-01-31', Chronos::createFromDate(2012, 1, 31)->addYears(-1)->toDateString());
    }

    public function testAddMonthPassingArg(): void
    {
        $this->assertSame(7, Chronos::createFromDate(1975, 5, 1)->addMonths(2)->month);
    }

    public function testAddMonthNoOverflowPassingArg(): void
    {
        $dt = Chronos::createFromDate(2010, 12, 31)->addMonths(2);
        $this->assertSame(2011, $dt->year);
        $this->assertSame(2, $dt->month);
        $this->assertSame(28, $dt->day);
    }

    public function testAddDayPassingArg(): void
    {
        $this->assertSame(12, Chronos::createFromDate(1975, 5, 10)->addDays(2)->day);
    }

    public function testAddHourPassingArg(): void
    {
        $this->assertSame(2, Chronos::createFromTime(0)->addHours(2)->hour);
    }

    public function testAddMinutePassingArg(): void
    {
        $this->assertSame(2, Chronos::createFromTime(0)->addMinutes(2)->minute);
    }

    public function testAddSecondPassingArg(): void
    {
        $this->assertSame(2, Chronos::createFromTime(0)->addSeconds(2)->second);
    }
}
