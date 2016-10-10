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

class DayOfWeekModifiersTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeek($class)
    {
        $d = $class::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeekFromWeekStart($class)
    {
        $d = $class::createFromDate(1980, 8, 4)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfWeekCrossingYearBoundary($class)
    {
        $d = $class::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->startOfWeek(), 2013, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeek($class)
    {
        $d = $class::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeekFromWeekEnd($class)
    {
        $d = $class::createFromDate(1980, 8, 9)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfWeekCrossingYearBoundary($class)
    {
        $d = $class::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->endOfWeek(), 2014, 1, 5, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNext($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next();
        $this->assertDateTime($d, 1975, 5, 28, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     */
    public function testStartOrEndOfWeekFromWeekWithUTC($class)
    {
        $d = $class::create(2016, 7, 27, 17, 13, 7, 'UTC');
        $this->assertDateTime($d->copy()->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->copy()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     */
    public function testStartOrEndOfWeekFromWeekWithOtherTimezone($class)
    {
        $d = $class::create(2016, 7, 27, 17, 13, 7, 'America/New_York');
        $this->assertDateTime($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextMonday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next($class::MONDAY);
        $this->assertDateTime($d, 1975, 5, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextSaturday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->next(6);
        $this->assertDateTime($d, 1975, 5, 24, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNextTimestamp($class)
    {
        $d = $class::createFromDate(1975, 11, 14)->next();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPrevious($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous();
        $this->assertDateTime($d, 1975, 5, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousMonday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous($class::MONDAY);
        $this->assertDateTime($d, 1975, 5, 19, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousSaturday($class)
    {
        $d = $class::createFromDate(1975, 5, 21)->previous(6);
        $this->assertDateTime($d, 1975, 5, 17, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testPreviousTimestamp($class)
    {
        $d = $class::createFromDate(1975, 11, 28)->previous();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth();
        $this->assertDateTime($d, 1975, 11, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth($class::WEDNESDAY);
        $this->assertDateTime($d, 1975, 11, 5, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfMonth(5);
        $this->assertDateTime($d, 1975, 11, 7, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->lastOfMonth();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 1)->lastOfMonth($class::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->lastOfMonth(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfMonthOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 12, 5)->nthOfMonth(6, $class::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfMonthOutsideYear($class)
    {
        $this->assertFalse($class::createFromDate(1975, 12, 5)->nthOfMonth(55, $class::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->nthOfMonth(2, $class::MONDAY);
        $this->assertDateTime($d, 1975, 12, 8, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfMonth($class)
    {
        $d = $class::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDateTime($d, 1975, 12, 17, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter();
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter($class::WEDNESDAY);
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDateTime($d, 1975, 10, 3, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->firstOfQuarter();
        $this->assertDateTime($d, 2014, 4, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->lastOfQuarter();
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 1)->lastOfQuarter($class::TUESDAY);
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDateTime($d, 1975, 9, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->lastOfQuarter();
        $this->assertDateTime($d, 2014, 6, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfQuarter(20, $class::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterOutsideYear($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfQuarter(55, $class::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth($class)
    {
        $d = $class::createFromDate(2014, 5, 31)->nthOfQuarter(2, $class::MONDAY);
        $this->assertDateTime($d, 2014, 4, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfQuarter(2, $class::MONDAY);
        $this->assertDateTime($d, 1975, 7, 14, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfQuarter($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDateTime($d, 1975, 7, 16, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstDayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear();
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstWednesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear($class::WEDNESDAY);
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFirstFridayOfYear($class)
    {
        $d = $class::createFromDate(1975, 11, 21)->firstOfYear(5);
        $this->assertDateTime($d, 1975, 1, 3, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastDayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->lastOfYear();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastTuesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 1)->lastOfYear($class::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLastFridayOfYear($class)
    {
        $d = $class::createFromDate(1975, 7, 5)->lastOfYear(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNthOfYearOutsideScope($class)
    {
        $this->assertFalse($class::createFromDate(1975, 1, 5)->nthOfYear(55, $class::MONDAY));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test2ndMondayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfYear(2, $class::MONDAY);
        $this->assertDateTime($d, 1975, 1, 13, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function test3rdWednesdayOfYear($class)
    {
        $d = $class::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDateTime($d, 1975, 1, 15, 0, 0, 0);
    }
}
