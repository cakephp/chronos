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
    public function testStartOfWeek(): void
    {
        $d = ChronosDate::create(1980, 8, 7)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4);
    }

    public function testStartOfWeekFromWeekStart(): void
    {
        $d = ChronosDate::create(1980, 8, 4)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4);
    }

    public function testStartOfWeekCrossingYearBoundary(): void
    {
        $d = ChronosDate::create(2013, 12, 31);
        $this->assertDate($d->startOfWeek(), 2013, 12, 30);
    }

    public function testEndOfWeek(): void
    {
        $d = ChronosDate::create(1980, 8, 7)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10);
    }

    public function testEndOfWeekFromWeekEnd(): void
    {
        $d = ChronosDate::create(1980, 8, 9)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10);
    }

    public function testEndOfWeekCrossingYearBoundary(): void
    {
        $d = ChronosDate::create(2013, 12, 31);
        $this->assertDate($d->endOfWeek(), 2014, 1, 5);
    }

    public function testNext(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->next();
        $this->assertDate($d, 1975, 5, 28);
    }

    public function testStartOrEndOfWeekFromWeekWithUTC(): void
    {
        $d = ChronosDate::create(2016, 7, 27);
        $this->assertDate($d->startOfWeek(), 2016, 7, 25);
        $this->assertDate($d->endOfWeek(), 2016, 7, 31);
        $this->assertDate($d->startOfWeek()->endOfWeek(), 2016, 7, 31);
    }

    public function testStartOrEndOfWeekFromWeekWithOtherTimezone(): void
    {
        $d = ChronosDate::create(2016, 7, 27);
        $this->assertDate($d->startOfWeek(), 2016, 7, 25);
        $this->assertDate($d->endOfWeek(), 2016, 7, 31);
        $this->assertDate($d->startOfWeek()->endOfWeek(), 2016, 7, 31);
    }

    public function testNextMonday(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->next(Chronos::MONDAY);
        $this->assertDate($d, 1975, 5, 26);
    }

    public function testNextSaturday(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->next(6);
        $this->assertDate($d, 1975, 5, 24);
    }

    public function testNextTimestamp(): void
    {
        $d = ChronosDate::create(1975, 11, 14)->next();
        $this->assertDate($d, 1975, 11, 21);
    }

    public function testPrevious(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->previous();
        $this->assertDate($d, 1975, 5, 14);
    }

    public function testPreviousMonday(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->previous(Chronos::MONDAY);
        $this->assertDate($d, 1975, 5, 19);
    }

    public function testPreviousSaturday(): void
    {
        $d = ChronosDate::create(1975, 5, 21)->previous(6);
        $this->assertDate($d, 1975, 5, 17);
    }

    public function testPreviousTimestamp(): void
    {
        $d = ChronosDate::create(1975, 11, 28)->previous();
        $this->assertDate($d, 1975, 11, 21);
    }

    public function testFirstDayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth();
        $this->assertDate($d, 1975, 11, 1);
    }

    public function testFirstWednesdayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 11, 5);
    }

    public function testFirstFridayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfMonth(5);
        $this->assertDate($d, 1975, 11, 7);
    }

    public function testLastDayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 12, 5)->lastOfMonth();
        $this->assertDate($d, 1975, 12, 31);
    }

    public function testLastTuesdayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 12, 1)->lastOfMonth(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 12, 30);
    }

    public function testLastFridayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 12, 5)->lastOfMonth(5);
        $this->assertDate($d, 1975, 12, 26);
    }

    public function testNthOfMonthOutsideScope(): void
    {
        $this->assertFalse(ChronosDate::create(1975, 12, 5)->nthOfMonth(6, Chronos::MONDAY));
    }

    public function testNthOfMonthOutsideYear(): void
    {
        $this->assertFalse(ChronosDate::create(1975, 12, 5)->nthOfMonth(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 12, 5)->nthOfMonth(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 12, 8);
    }

    public function test3rdWednesdayOfMonth(): void
    {
        $d = ChronosDate::create(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDate($d, 1975, 12, 17);
    }

    public function testFirstDayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter();
        $this->assertDate($d, 1975, 10, 1);
    }

    public function testFirstWednesdayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 10, 1);
    }

    public function testFirstFridayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDate($d, 1975, 10, 3);
    }

    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth(): void
    {
        $d = ChronosDate::create(2014, 5, 31)->firstOfQuarter();
        $this->assertDate($d, 2014, 4, 1);
    }

    public function testLastDayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->lastOfQuarter();
        $this->assertDate($d, 1975, 9, 30);
    }

    public function testLastTuesdayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 8, 1)->lastOfQuarter(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 9, 30);
    }

    public function testLastFridayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDate($d, 1975, 9, 26);
    }

    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth(): void
    {
        $d = ChronosDate::create(2014, 5, 31)->lastOfQuarter();
        $this->assertDate($d, 2014, 6, 30);
    }

    public function testNthOfQuarterOutsideScope(): void
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfQuarter(20, Chronos::MONDAY));
    }

    public function testNthOfQuarterOutsideYear(): void
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfQuarter(55, Chronos::MONDAY));
    }

    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth(): void
    {
        $d = ChronosDate::create(2014, 5, 31)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDate($d, 2014, 4, 14);
    }

    public function test2ndMondayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 7, 14);
    }

    public function test3rdWednesdayOfQuarter(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDate($d, 1975, 7, 16);
    }

    public function testFirstDayOfYear(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear();
        $this->assertDate($d, 1975, 1, 1);
    }

    public function testFirstWednesdayOfYear(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear(Chronos::WEDNESDAY);
        $this->assertDate($d, 1975, 1, 1);
    }

    public function testFirstFridayOfYear(): void
    {
        $d = ChronosDate::create(1975, 11, 21)->firstOfYear(5);
        $this->assertDate($d, 1975, 1, 3);
    }

    public function testLastDayOfYear(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->lastOfYear();
        $this->assertDate($d, 1975, 12, 31);
    }

    public function testLastTuesdayOfYear(): void
    {
        $d = ChronosDate::create(1975, 8, 1)->lastOfYear(Chronos::TUESDAY);
        $this->assertDate($d, 1975, 12, 30);
    }

    public function testLastFridayOfYear(): void
    {
        $d = ChronosDate::create(1975, 7, 5)->lastOfYear(5);
        $this->assertDate($d, 1975, 12, 26);
    }

    public function testNthOfYearOutsideScope(): void
    {
        $this->assertFalse(ChronosDate::create(1975, 1, 5)->nthOfYear(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfYear(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfYear(2, Chronos::MONDAY);
        $this->assertDate($d, 1975, 1, 13);
    }

    public function test3rdWednesdayOfYear(): void
    {
        $d = ChronosDate::create(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDate($d, 1975, 1, 15);
    }
}
