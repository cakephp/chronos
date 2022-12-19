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

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

class DayOfWeekModifiersTest extends TestCase
{
    public function testStartOfWeek()
    {
        $d = ChronosDate::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekFromWeekStart()
    {
        $d = ChronosDate::create(1980, 8, 4)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekCrossingYearBoundary()
    {
        $d = ChronosDate::create(2013, 12, 31, 'GMT');
        $this->assertDate($d->startOfWeek(), 2013, 12, 30, 0, 0, 0);
    }

    public function testEndOfWeek()
    {
        $d = ChronosDate::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekFromWeekEnd()
    {
        $d = ChronosDate::create(1980, 8, 9)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekCrossingYearBoundary()
    {
        $d = ChronosDate::create(2013, 12, 31, 'GMT');
        $this->assertDate($d->endOfWeek(), 2014, 1, 5, 23, 59, 59);
    }

    public function testNext()
    {
        $d = ChronosDate::create(1975, 5, 21)->next();
        $this->assertDate($d, 1975, 5, 28, 0, 0, 0);
    }

    public function testStartOrEndOfWeekFromWeekWithUTC()
    {
        $d = ChronosDate::create(2016, 7, 27, 17, 13, 7, 0, 'UTC');
        $this->assertDate($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDate($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDate($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testStartOrEndOfWeekFromWeekWithOtherTimezone()
    {
        $d = ChronosDate::create(2016, 7, 27, 17, 13, 7, 0, 'America/New_York');
        $this->assertDate($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDate($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDate($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testNextMonday()
    {
        $d = ChronosDate::create(1975, 5, 21)->next(Chronos::MONDAY);
        $this->assertDate($d, 1975, 5, 26, 0, 0, 0);
    }

    public function testNextSaturday()
    {
        $d = ChronosDate::create(1975, 5, 21)->next(6);
        $this->assertDate($d, 1975, 5, 24, 0, 0, 0);
    }

    public function testNextTimestamp()
    {
        $d = ChronosDate::create(1975, 11, 14)->next();
        $this->assertDate($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testPrevious()
    {
        $d = ChronosDate::create(1975, 5, 21)->previous();
        $this->assertDate($d, 1975, 5, 14, 0, 0, 0);
    }

    public function testPreviousMonday()
    {
        $d = ChronosDate::create(1975, 5, 21)->previous(Chronos::MONDAY);
        $this->assertDate($d, 1975, 5, 19, 0, 0, 0);
    }

    public function testPreviousSaturday()
    {
        $d = ChronosDate::create(1975, 5, 21)->previous(6);
        $this->assertDate($d, 1975, 5, 17, 0, 0, 0);
    }

    public function testPreviousTimestamp()
    {
        $d = ChronosDate::create(1975, 11, 28)->previous();
        $this->assertDate($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testFirstDayOfMonth()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth();
        $this->assertDate($d, 1975, 11, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfMonth()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 11, 5, 0, 0, 0);
    }

    public function testFirstFridayOfMonth()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth(5);
        $this->assertDate($d, 1975, 11, 7, 0, 0, 0);
    }

    public function testLastDayOfMonth()
    {
        $d = ChronosDate::create(1975, 12, 5)->lastOfMonth();
        $this->assertDate($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfMonth()
    {
        $d = ChronosDate::create(1975, 12, 1)->lastOfMonth(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfMonth()
    {
        $d = ChronosDate::create(1975, 12, 5)->lastOfMonth(5);
        $this->assertDate($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfMonthOutsideScope()
    {
        $this->assertFalse(ChronosDate::create(1975, 12, 5)->nthOfMonth(6, Chronos::MONDAY));
    }

    public function testNthOfMonthOutsideYear()
    {
        $this->assertFalse(ChronosDate::create(1975, 12, 5)->nthOfMonth(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfMonth()
    {
        $d = ChronosDate::create(1975, 12, 5)->nthOfMonth(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 12, 8, 0, 0, 0);
    }

    public function test3rdWednesdayOfMonth()
    {
        $d = ChronosDate::create(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDate($d, 1975, 12, 17, 0, 0, 0);
    }

    public function testFirstDayOfQuarter()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter();
        $this->assertDate($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfQuarter()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstFridayOfQuarter()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDate($d, 1975, 10, 3, 0, 0, 0);
    }

    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = ChronosDate::create(2014, 5, 31)->firstOfQuarter();
        $this->assertDate($d, 2014, 4, 1, 0, 0, 0);
    }

    public function testLastDayOfQuarter()
    {
        $d = ChronosDate::create(1975, 8, 5)->lastOfQuarter();
        $this->assertDate($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastTuesdayOfQuarter()
    {
        $d = ChronosDate::create(1975, 8, 1)->lastOfQuarter(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastFridayOfQuarter()
    {
        $d = ChronosDate::create(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDate($d, 1975, 9, 26, 0, 0, 0);
    }

    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth()
    {
        $d = ChronosDate::create(2014, 5, 31)->lastOfQuarter();
        $this->assertDate($d, 2014, 6, 30, 0, 0, 0);
    }

    public function testNthOfQuarterOutsideScope()
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfQuarter(20, Chronos::MONDAY));
    }

    public function testNthOfQuarterOutsideYear()
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfQuarter(55, Chronos::MONDAY));
    }

    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = ChronosDate::create(2014, 5, 31)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDate($d, 2014, 4, 14, 0, 0, 0);
    }

    public function test2ndMondayOfQuarter()
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 7, 14, 0, 0, 0);
    }

    public function test3rdWednesdayOfQuarter()
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDate($d, 1975, 7, 16, 0, 0, 0);
    }

    public function testFirstDayOfYear()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear();
        $this->assertDate($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfYear()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstFridayOfYear()
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear(5);
        $this->assertDate($d, 1975, 1, 3, 0, 0, 0);
    }

    public function testLastDayOfYear()
    {
        $d = ChronosDate::create(1975, 8, 5)->lastOfYear();
        $this->assertDate($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfYear()
    {
        $d = ChronosDate::create(1975, 8, 1)->lastOfYear(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfYear()
    {
        $d = ChronosDate::create(1975, 7, 5)->lastOfYear(5);
        $this->assertDate($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfYearOutsideScope()
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfYear(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfYear()
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfYear(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 1, 13, 0, 0, 0);
    }

    public function test3rdWednesdayOfYear()
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDate($d, 1975, 1, 15, 0, 0, 0);
    }
}
