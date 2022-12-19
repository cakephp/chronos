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

class DayOfWeekModifiersTest extends TestCase
{
    public function testStartOfWeek()
    {
        $d = Chronos::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekFromWeekStart()
    {
        $d = Chronos::createFromDate(1980, 8, 4)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekCrossingYearBoundary()
    {
        $d = Chronos::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->startOfWeek(), 2013, 12, 30, 0, 0, 0);
    }

    public function testEndOfWeek()
    {
        $d = Chronos::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekFromWeekEnd()
    {
        $d = Chronos::createFromDate(1980, 8, 9)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekCrossingYearBoundary()
    {
        $d = Chronos::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->endOfWeek(), 2014, 1, 5, 23, 59, 59);
    }

    public function testNext()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next();
        $this->assertDateTime($d, 1975, 5, 28, 0, 0, 0);
    }

    public function testStartOrEndOfWeekFromWeekWithUTC()
    {
        $d = Chronos::create(2016, 7, 27, 17, 13, 7, 0, 'UTC');
        $this->assertDateTime($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testStartOrEndOfWeekFromWeekWithOtherTimezone()
    {
        $d = Chronos::create(2016, 7, 27, 17, 13, 7, 0, 'America/New_York');
        $this->assertDateTime($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testNextMonday()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next(Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 5, 26, 0, 0, 0);
    }

    public function testNextSaturday()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next(6);
        $this->assertDateTime($d, 1975, 5, 24, 0, 0, 0);
    }

    public function testNextTimestamp()
    {
        $d = Chronos::createFromDate(1975, 11, 14)->next();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testPrevious()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous();
        $this->assertDateTime($d, 1975, 5, 14, 0, 0, 0);
    }

    public function testPreviousMonday()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous(Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 5, 19, 0, 0, 0);
    }

    public function testPreviousSaturday()
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous(6);
        $this->assertDateTime($d, 1975, 5, 17, 0, 0, 0);
    }

    public function testPreviousTimestamp()
    {
        $d = Chronos::createFromDate(1975, 11, 28)->previous();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testFirstDayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth();
        $this->assertDateTime($d, 1975, 11, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 11, 5, 0, 0, 0);
    }

    public function testFirstFridayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth(5);
        $this->assertDateTime($d, 1975, 11, 7, 0, 0, 0);
    }

    public function testLastDayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 12, 5)->lastOfMonth();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 12, 1)->lastOfMonth(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 12, 5)->lastOfMonth(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfMonthOutsideScope()
    {
        $this->assertFalse(Chronos::createFromDate(1975, 12, 5)->nthOfMonth(6, Chronos::MONDAY));
    }

    public function testNthOfMonthOutsideYear()
    {
        $this->assertFalse(Chronos::createFromDate(1975, 12, 5)->nthOfMonth(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 12, 5)->nthOfMonth(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 12, 8, 0, 0, 0);
    }

    public function test3rdWednesdayOfMonth()
    {
        $d = Chronos::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDateTime($d, 1975, 12, 17, 0, 0, 0);
    }

    public function testFirstDayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter();
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstFridayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDateTime($d, 1975, 10, 3, 0, 0, 0);
    }

    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = Chronos::createFromDate(2014, 5, 31)->firstOfQuarter();
        $this->assertDateTime($d, 2014, 4, 1, 0, 0, 0);
    }

    public function testLastDayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->lastOfQuarter();
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastTuesdayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 8, 1)->lastOfQuarter(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastFridayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDateTime($d, 1975, 9, 26, 0, 0, 0);
    }

    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth()
    {
        $d = Chronos::createFromDate(2014, 5, 31)->lastOfQuarter();
        $this->assertDateTime($d, 2014, 6, 30, 0, 0, 0);
    }

    public function testNthOfQuarterOutsideScope()
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfQuarter(20, Chronos::MONDAY));
    }

    public function testNthOfQuarterOutsideYear()
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfQuarter(55, Chronos::MONDAY));
    }

    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = Chronos::createFromDate(2014, 5, 31)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDateTime($d, 2014, 4, 14, 0, 0, 0);
    }

    public function test2ndMondayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 7, 14, 0, 0, 0);
    }

    public function test3rdWednesdayOfQuarter()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDateTime($d, 1975, 7, 16, 0, 0, 0);
    }

    public function testFirstDayOfYear()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear();
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfYear()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstFridayOfYear()
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear(5);
        $this->assertDateTime($d, 1975, 1, 3, 0, 0, 0);
    }

    public function testLastDayOfYear()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->lastOfYear();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfYear()
    {
        $d = Chronos::createFromDate(1975, 8, 1)->lastOfYear(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfYear()
    {
        $d = Chronos::createFromDate(1975, 7, 5)->lastOfYear(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfYearOutsideScope()
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfYear(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfYear()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfYear(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 1, 13, 0, 0, 0);
    }

    public function test3rdWednesdayOfYear()
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDateTime($d, 1975, 1, 15, 0, 0, 0);
    }
}
