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
    public function testStartOfWeek(): void
    {
        $d = Chronos::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekFromWeekStart(): void
    {
        $d = Chronos::createFromDate(1980, 8, 4)->startOfWeek();
        $this->assertDateTime($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekCrossingYearBoundary(): void
    {
        $d = Chronos::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->startOfWeek(), 2013, 12, 30, 0, 0, 0);
    }

    public function testEndOfWeek(): void
    {
        $d = Chronos::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekFromWeekEnd(): void
    {
        $d = Chronos::createFromDate(1980, 8, 9)->endOfWeek();
        $this->assertDateTime($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekCrossingYearBoundary(): void
    {
        $d = Chronos::createFromDate(2013, 12, 31, 'GMT');
        $this->assertDateTime($d->endOfWeek(), 2014, 1, 5, 23, 59, 59);
    }

    public function testNext(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next();
        $this->assertDateTime($d, 1975, 5, 28, 0, 0, 0);
    }

    public function testStartOrEndOfWeekFromWeekWithUTC(): void
    {
        $d = Chronos::create(2016, 7, 27, 17, 13, 7, 0, 'UTC');
        $this->assertDateTime($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testStartOrEndOfWeekFromWeekWithOtherTimezone(): void
    {
        $d = Chronos::create(2016, 7, 27, 17, 13, 7, 0, 'America/New_York');
        $this->assertDateTime($d->startOfWeek(), 2016, 7, 25, 0, 0, 0);
        $this->assertDateTime($d->endOfWeek(), 2016, 7, 31, 23, 59, 59);
        $this->assertDateTime($d->startOfWeek()->endOfWeek(), 2016, 7, 31, 23, 59, 59);
    }

    public function testNextMonday(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next(Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 5, 26, 0, 0, 0);
    }

    public function testNextSaturday(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->next(6);
        $this->assertDateTime($d, 1975, 5, 24, 0, 0, 0);
    }

    public function testNextTimestamp(): void
    {
        $d = Chronos::createFromDate(1975, 11, 14)->next();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testPrevious(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous();
        $this->assertDateTime($d, 1975, 5, 14, 0, 0, 0);
    }

    public function testPreviousMonday(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous(Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 5, 19, 0, 0, 0);
    }

    public function testPreviousSaturday(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21)->previous(6);
        $this->assertDateTime($d, 1975, 5, 17, 0, 0, 0);
    }

    public function testPreviousTimestamp(): void
    {
        $d = Chronos::createFromDate(1975, 11, 28)->previous();
        $this->assertDateTime($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testFirstDayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth();
        $this->assertDateTime($d, 1975, 11, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 11, 5, 0, 0, 0);
    }

    public function testFirstFridayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfMonth(5);
        $this->assertDateTime($d, 1975, 11, 7, 0, 0, 0);
    }

    public function testLastDayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 12, 5)->lastOfMonth();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 12, 1)->lastOfMonth(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 12, 5)->lastOfMonth(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfMonthOutsideScope(): void
    {
        $this->assertFalse(Chronos::createFromDate(1975, 12, 5)->nthOfMonth(6, Chronos::MONDAY));
    }

    public function testNthOfMonthOutsideYear(): void
    {
        $this->assertFalse(Chronos::createFromDate(1975, 12, 5)->nthOfMonth(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 12, 5)->nthOfMonth(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 12, 8, 0, 0, 0);
    }

    public function test3rdWednesdayOfMonth(): void
    {
        $d = Chronos::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDateTime($d, 1975, 12, 17, 0, 0, 0);
    }

    public function testFirstDayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter();
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstFridayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDateTime($d, 1975, 10, 3, 0, 0, 0);
    }

    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth(): void
    {
        $d = Chronos::createFromDate(2014, 5, 31)->firstOfQuarter();
        $this->assertDateTime($d, 2014, 4, 1, 0, 0, 0);
    }

    public function testLastDayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->lastOfQuarter();
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastTuesdayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 8, 1)->lastOfQuarter(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastFridayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDateTime($d, 1975, 9, 26, 0, 0, 0);
    }

    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth(): void
    {
        $d = Chronos::createFromDate(2014, 5, 31)->lastOfQuarter();
        $this->assertDateTime($d, 2014, 6, 30, 0, 0, 0);
    }

    public function testNthOfQuarterOutsideScope(): void
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfQuarter(20, Chronos::MONDAY));
    }

    public function testNthOfQuarterOutsideYear(): void
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfQuarter(55, Chronos::MONDAY));
    }

    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth(): void
    {
        $d = Chronos::createFromDate(2014, 5, 31)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDateTime($d, 2014, 4, 14, 0, 0, 0);
    }

    public function test2ndMondayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfQuarter(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 7, 14, 0, 0, 0);
    }

    public function test3rdWednesdayOfQuarter(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDateTime($d, 1975, 7, 16, 0, 0, 0);
    }

    public function testFirstDayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear();
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear(Chronos::WEDNESDAY);
        $this->assertDateTime($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstFridayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 11, 21)->firstOfYear(5);
        $this->assertDateTime($d, 1975, 1, 3, 0, 0, 0);
    }

    public function testLastDayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->lastOfYear();
        $this->assertDateTime($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 8, 1)->lastOfYear(Chronos::TUESDAY);
        $this->assertDateTime($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 7, 5)->lastOfYear(5);
        $this->assertDateTime($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfYearOutsideScope(): void
    {
        $this->assertFalse(Chronos::createFromDate(1975, 1, 5)->nthOfYear(55, Chronos::MONDAY));
    }

    public function test2ndMondayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfYear(2, Chronos::MONDAY);
        $this->assertDateTime($d, 1975, 1, 13, 0, 0, 0);
    }

    public function test3rdWednesdayOfYear(): void
    {
        $d = Chronos::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDateTime($d, 1975, 1, 15, 0, 0, 0);
    }

    /**
     * Test nextOccurrenceOf when today is the target day and time hasn't passed.
     */
    public function testNextOccurrenceOfSameDayBeforeTime(): void
    {
        // It's Tuesday 9am, looking for Tuesday 12pm -> should be today
        $d = Chronos::create(2024, 1, 9, 9, 0, 0); // Tuesday
        $result = $d->nextOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 9, 12, 0, 0);
    }

    /**
     * Test nextOccurrenceOf when today is the target day but time has passed.
     */
    public function testNextOccurrenceOfSameDayAfterTime(): void
    {
        // It's Tuesday 4pm, looking for Tuesday 12pm -> should be next Tuesday
        $d = Chronos::create(2024, 1, 9, 16, 0, 0); // Tuesday
        $result = $d->nextOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 16, 12, 0, 0);
    }

    /**
     * Test nextOccurrenceOf when today is not the target day.
     */
    public function testNextOccurrenceOfDifferentDay(): void
    {
        // It's Monday, looking for Tuesday 12pm -> should be tomorrow
        $d = Chronos::create(2024, 1, 8, 9, 0, 0); // Monday
        $result = $d->nextOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 9, 12, 0, 0);
    }

    /**
     * Test nextOccurrenceOf with seconds.
     */
    public function testNextOccurrenceOfWithSeconds(): void
    {
        $d = Chronos::create(2024, 1, 8, 9, 0, 0); // Monday
        $result = $d->nextOccurrenceOf(Chronos::WEDNESDAY, 14, 30, 45);
        $this->assertDateTime($result, 2024, 1, 10, 14, 30, 45);
    }

    /**
     * Test nextOccurrenceOf at exact same time returns next week.
     */
    public function testNextOccurrenceOfAtExactTime(): void
    {
        // It's Tuesday 12pm exactly, looking for Tuesday 12pm -> should be next week
        $d = Chronos::create(2024, 1, 9, 12, 0, 0); // Tuesday 12pm
        $result = $d->nextOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 16, 12, 0, 0);
    }

    /**
     * Test previousOccurrenceOf when today is the target day and time has passed.
     */
    public function testPreviousOccurrenceOfSameDayAfterTime(): void
    {
        // It's Tuesday 4pm, looking for previous Tuesday 12pm -> should be today
        $d = Chronos::create(2024, 1, 9, 16, 0, 0); // Tuesday
        $result = $d->previousOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 9, 12, 0, 0);
    }

    /**
     * Test previousOccurrenceOf when today is the target day but time hasn't passed.
     */
    public function testPreviousOccurrenceOfSameDayBeforeTime(): void
    {
        // It's Tuesday 9am, looking for previous Tuesday 12pm -> should be last Tuesday
        $d = Chronos::create(2024, 1, 9, 9, 0, 0); // Tuesday
        $result = $d->previousOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 2, 12, 0, 0);
    }

    /**
     * Test previousOccurrenceOf when today is not the target day.
     */
    public function testPreviousOccurrenceOfDifferentDay(): void
    {
        // It's Wednesday, looking for previous Tuesday 12pm -> should be yesterday
        $d = Chronos::create(2024, 1, 10, 9, 0, 0); // Wednesday
        $result = $d->previousOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 9, 12, 0, 0);
    }

    /**
     * Test previousOccurrenceOf at exact same time returns last week.
     */
    public function testPreviousOccurrenceOfAtExactTime(): void
    {
        // It's Tuesday 12pm exactly, looking for previous Tuesday 12pm -> should be last week
        $d = Chronos::create(2024, 1, 9, 12, 0, 0); // Tuesday 12pm
        $result = $d->previousOccurrenceOf(Chronos::TUESDAY, 12, 0);
        $this->assertDateTime($result, 2024, 1, 2, 12, 0, 0);
    }
}
