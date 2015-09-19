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

class DayOfWeekModifiersTest extends TestFixture
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeek($class)
    {
        $d = $class::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertCarbon($d, 1980, 8, 4, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeekFromWeekStart($class)
    {
        $d = $class::createFromDate(1980, 8, 4)->startOfWeek();
        $this->assertCarbon($d, 1980, 8, 4, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeekCrossingYearBoundary($class)
    {
        $d = $class::createFromDate(2013, 12, 31, 'GMT');
        $this->assertCarbon($d->startOfWeek(), 2013, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeek($class)
    {
        $d = $class::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertCarbon($d, 1980, 8, 10, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeekFromWeekEnd($class)
    {
        $d = $class::createFromDate(1980, 8, 9)->endOfWeek();
        $this->assertCarbon($d, 1980, 8, 10, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeekCrossingYearBoundary($class)
    {
        $d = $class::createFromDate(2013, 12, 31, 'GMT');
        $this->assertCarbon($d->endOfWeek(), 2014, 1, 5, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNext($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next();
        $this->assertCarbon($d, 1975, 5, 28, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextMonday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next(Carbon::MONDAY);
        $this->assertCarbon($d, 1975, 5, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextSaturday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next(6);
        $this->assertCarbon($d, 1975, 5, 24, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextTimestamp($class)
    {
        $d = $class::createFromDate(1975, 11, 14)->next();
        $this->assertCarbon($d, 1975, 11, 21, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPrevious($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous();
        $this->assertCarbon($d, 1975, 5, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousMonday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous(Carbon::MONDAY);
        $this->assertCarbon($d, 1975, 5, 19, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousSaturday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous(6);
        $this->assertCarbon($d, 1975, 5, 17, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousTimestamp($class)
    {
        $d = $class::createFromDate(1975, 11, 28)->previous();
        $this->assertCarbon($d, 1975, 11, 21, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth();
        $this->assertCarbon($d, 1975, 11, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth(Carbon::WEDNESDAY);
        $this->assertCarbon($d, 1975, 11, 5, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth(5);
        $this->assertCarbon($d, 1975, 11, 7, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->lastOfMonth();
        $this->assertCarbon($d, 1975, 12, 31, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 1)->lastOfMonth(Carbon::TUESDAY);
        $this->assertCarbon($d, 1975, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->lastOfMonth(5);
        $this->assertCarbon($d, 1975, 12, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfMonthOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 12, 5)->nthOfMonth(6, Carbon::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfMonthOutsideYear($class)
    {
        $this->assertFalse($class::createFromDate(1975, 12, 5)->nthOfMonth(55, Carbon::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->nthOfMonth(2, Carbon::MONDAY);
        $this->assertCarbon($d, 1975, 12, 8, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertCarbon($d, 1975, 12, 17, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter();
        $this->assertCarbon($d, 1975, 10, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter(Carbon::WEDNESDAY);
        $this->assertCarbon($d, 1975, 10, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter(5);
        $this->assertCarbon($d, 1975, 10, 3, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->firstOfQuarter();
        $this->assertCarbon($d, 2014, 4, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->lastOfQuarter();
        $this->assertCarbon($d, 1975, 9, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 1)->lastOfQuarter(Carbon::TUESDAY);
        $this->assertCarbon($d, 1975, 9, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 7, 5)->lastOfQuarter(5);
        $this->assertCarbon($d, 1975, 9, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->lastOfQuarter();
        $this->assertCarbon($d, 2014, 6, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfQuarter(20, Carbon::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterOutsideYear($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfQuarter(55, Carbon::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->nthOfQuarter(2, Carbon::MONDAY);
        $this->assertCarbon($d, 2014, 4, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfQuarter(2, Carbon::MONDAY);
        $this->assertCarbon($d, 1975, 7, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertCarbon($d, 1975, 7, 16, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear();
        $this->assertCarbon($d, 1975, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear(Carbon::WEDNESDAY);
        $this->assertCarbon($d, 1975, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear(5);
        $this->assertCarbon($d, 1975, 1, 3, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->lastOfYear();
        $this->assertCarbon($d, 1975, 12, 31, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 1)->lastOfYear(Carbon::TUESDAY);
        $this->assertCarbon($d, 1975, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfYear($class)
    {
        $d = $class::createFromDate(1975, 7, 5)->lastOfYear(5);
        $this->assertCarbon($d, 1975, 12, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfYearOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfYear(55, Carbon::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfYear(2, Carbon::MONDAY);
        $this->assertCarbon($d, 1975, 1, 13, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertCarbon($d, 1975, 1, 15, 0, 0, 0);
    }
}
