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

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterval;
use Closure;
use TestCase;

class DiffTest extends TestCase
{

    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        parent::wrapWithTestNow($func, ($dt === null) ? Chronos::createFromDate(2012, 1, 1) : $dt);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInYearsPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->addYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInYearsNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-1, $dt->diffInYears($dt->copy()->subYear(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInYearsNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->subYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInYearsVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(1, $class::now()->subYear()->diffInYears());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInYearsEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->addYear()->addMonths(7)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMonthsPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(13, $dt->diffInMonths($dt->copy()->addYear()->addMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMonthsNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-11, $dt->diffInMonths($dt->copy()->subYear()->addMonth(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMonthsNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(11, $dt->diffInMonths($dt->copy()->subYear()->addMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMonthsVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(12, $class::now()->subYear()->diffInMonths());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMonthsEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMonths($dt->copy()->addMonth()->addDays(16)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(366, $dt->diffInDays($dt->copy()->addYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-365, $dt->diffInDays($dt->copy()->subYear(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(365, $dt->diffInDays($dt->copy()->subYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(7, $class::now()->subWeek()->diffInDays());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInDays($dt->copy()->addDay()->addHours(13)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredPositiveWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date) {
            return $date->dayOfWeek === 1;
        }, $dt->copy()->endOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredPositiveWithSecondObject($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 1);
        $dt2 = $class::createFromDate(2000, 1, 31);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeNoSignWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt->copy()->startOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeNoSignWithSecondObject($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 31);
        $dt2 = $class::createFromDate(2000, 1, 1);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeWithSignWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(-5, $dt->diffInDaysFiltered(function ($date) {
            return $date->dayOfWeek === 1;
        }, $dt->copy()->startOfMonth(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeWithSignWithSecondObject($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 31);
        $dt2 = $class::createFromDate(2000, 1, 1);

        $this->assertSame(-5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2, false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursFiltered($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = $class::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(31, $dt1->diffInHoursFiltered(function ($date) {
            return $date->hour === 9;
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursFilteredNegative($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = $class::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(-31, $dt1->diffInHoursFiltered(function ($date) {
            return $date->hour === 9;
        }, $dt2, false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursFilteredWorkHoursPerWeek($class)
    {
        $dt1 = $class::createFromDate(2000, 1, 5)->endOfDay();
        $dt2 = $class::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(40, $dt1->diffInHoursFiltered(function ($date) {
            return ($date->hour > 8 && $date->hour < 17);
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredUsingMinutesPositiveWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1)->startOfDay();
        $this->assertSame(60, $dt->diffFiltered(ChronosInterval::minute(), function ($date) {
            return $date->hour === 12;
        }, $class::createFromDate(2000, 1, 1)->endOfDay()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredPositiveWithSecondObject($class)
    {
        $dt1 = $class::create(2000, 1, 1);
        $dt2 = $dt1->copy()->addSeconds(80);

        $this->assertSame(40, $dt1->diffFiltered(ChronosInterval::second(), function ($date) {
            return $date->second % 2 === 0;
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredNegativeNoSignWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);

        $this->assertSame(2, $dt->diffFiltered(ChronosInterval::days(2), function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt->copy()->startOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredNegativeNoSignWithSecondObject($class)
    {
        $dt1 = $class::createFromDate(2006, 1, 31);
        $dt2 = $class::createFromDate(2000, 1, 1);

        $this->assertSame(7, $dt1->diffFiltered(ChronosInterval::year(), function ($date) {
            return $date->month === 1;
        }, $dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredNegativeWithSignWithMutated($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(-4, $dt->diffFiltered(ChronosInterval::week(), function ($date) {
            return $date->month === 12;
        }, $dt->copy()->subMonths(3), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffFilteredNegativeWithSignWithSecondObject($class)
    {
        $dt1 = $class::createFromDate(2001, 1, 31);
        $dt2 = $class::createFromDate(1999, 1, 1);

        $this->assertSame(-12, $dt1->diffFiltered(ChronosInterval::month(), function ($date) {
            return $date->year === 2000;
        }, $dt2, false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBug188DiffWithSameDates($class)
    {
        $start = $class::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy();

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBug188DiffWithDatesOnlyHoursApart($class)
    {
        $start = $class::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy();

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBug188DiffWithSameDates1DayApart($class)
    {
        $start = $class::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy()->addDay();

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(1, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBug188DiffWithDatesOnTheWeekend($class)
    {
        $start = $class::create(2014, 1, 1, 0, 0, 0);
        $start = $start->next($class::SATURDAY);
        $end = $start->copy()->addDay();

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekdaysPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(21, $dt->diffInWeekdays($dt->copy()->endOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekdaysNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(21, $dt->diffInWeekdays($dt->copy()->startOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekdaysNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(-21, $dt->diffInWeekdays($dt->copy()->startOfMonth(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekendDaysPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->copy()->endOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekendDaysNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->copy()->startOfMonth()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeekendDaysNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 31);
        $this->assertSame(-10, $dt->diffInWeekendDays($dt->copy()->startOfMonth(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeeksPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->copy()->addYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeeksNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-52, $dt->diffInWeeks($dt->copy()->subYear(), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeeksNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->copy()->subYear()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeeksVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(1, $class::now()->subWeek()->diffInWeeks());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInWeeksEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(0, $dt->diffInWeeks($dt->copy()->addWeek()->subDay()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(26, $dt->diffInHours($dt->copy()->addDay()->addHours(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-22, $dt->diffInHours($dt->copy()->subDay()->addHours(2), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(22, $dt->diffInHours($dt->copy()->subDay()->addHours(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursVsDefaultNow($class)
    {
        date_default_timezone_set('UTC');
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(48, $class::now()->subDays(2)->diffInHours());
        }, $class::create(2012, 1, 15));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInHoursEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInHours($dt->copy()->addHour()->addMinutes(31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInMinutes($dt->copy()->addHour()->addMinutes(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesPositiveAlot($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1502, $dt->diffInMinutes($dt->copy()->addHours(25)->addMinutes(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInMinutes($dt->copy()->subHour()->addMinutes(2), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInMinutes($dt->copy()->subHour()->addMinutes(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(60, $class::now()->subHour()->diffInMinutes());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInMinutesEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMinutes($dt->copy()->addMinute()->addSeconds(31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsPositive($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInSeconds($dt->copy()->addMinute()->addSeconds(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsPositiveAlot($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(7202, $dt->diffInSeconds($dt->copy()->addHours(2)->addSeconds(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsNegativeWithSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInSeconds($dt->copy()->subMinute()->addSeconds(2), false));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsNegativeNoSign($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInSeconds($dt->copy()->subMinute()->addSeconds(2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(3600, $class::now()->subHour()->diffInSeconds());
        });
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsEnsureIsTruncated($class)
    {
        $dt = $class::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInSeconds($dt->copy()->addSeconds(1.9)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsWithTimezones($class)
    {
        $dtOttawa = $class::createFromDate(2000, 1, 1, 'America/Toronto');
        $dtVancouver = $class::createFromDate(2000, 1, 1, 'America/Vancouver');
        $this->assertSame(3 * 60 * 60, $dtOttawa->diffInSeconds($dtVancouver));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDiffInSecondsWithTimezonesAndVsDefault($class)
    {
        $vanNow = $class::now('America/Vancouver');
        $hereNow = $vanNow->copy()->setTimezone($class::now()->tz);

        $this->wrapWithTestNow(function () use ($vanNow) {
            $this->assertSame(0, $vanNow->diffInSeconds());
        }, $hereNow);
    }

    /**
     * Tests the "from now" time calculation.
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFromNow($class)
    {
        $date = $class::now();
        $date = $date->modify('-1 year')
            ->modify('-6 days')
            ->modify('-51 seconds');
        $interval = $class::fromNow($date);
        $result = $interval->format("%y %m %d %H %i %s");
        $this->assertEquals($result, '1 0 6 00 0 51');
    }

    public function diffForHumansProvider()
    {
        $now = Chronos::now();

        return [
            [$now, $now->addYears(11), '11 years before'],
            [$now, $now->addYears(1), '1 year before'],
            [$now, $now->addMonths(11), '11 months before'],
            [$now, $now->addMonths(1), '1 month before'],
            [$now, $now->addDays(8), '1 week before'],
            [$now, $now->addDays(23), '3 weeks before'],
            [$now, $now->addDays(1), '1 day before'],
            [$now, $now->addDays(6), '6 days before'],
            [$now, $now->addHours(1), '1 hour before'],
            [$now, $now->addHours(5), '5 hours before'],
            [$now, $now->addHours(23), '23 hours before'],
            [$now, $now->addMinutes(1), '1 minute before'],
            [$now, $now->addMinutes(5), '5 minutes before'],
            [$now, $now->addMinutes(59), '59 minutes before'],
            [$now, $now->addSeconds(1), '1 second before'],
            [$now, $now->addSeconds(5), '5 seconds before'],
            [$now, $now->addSeconds(59), '59 seconds before'],

            [$now, $now->subYears(11), '11 years after'],
            [$now, $now->subYears(1), '1 year after'],
            [$now, $now->subMonths(11), '11 months after'],
            [$now, $now->subMonths(1), '1 month after'],
            [$now, $now->subDays(8), '1 week after'],
            [$now, $now->subDays(23), '3 weeks after'],
            [$now, $now->subDays(1), '1 day after'],
            [$now, $now->subDays(6), '6 days after'],
            [$now, $now->subHours(1), '1 hour after'],
            [$now, $now->subHours(5), '5 hours after'],
            [$now, $now->subHours(23), '23 hours after'],
            [$now, $now->subMinutes(1), '1 minute after'],
            [$now, $now->subMinutes(5), '5 minutes after'],
            [$now, $now->subMinutes(59), '59 minutes after'],
            [$now, $now->subSeconds(1), '1 second after'],
            [$now, $now->subSeconds(5), '5 seconds after'],
            [$now, $now->subSeconds(59), '59 seconds after'],
        ];
    }

    /**
     * @dataProvider diffForHumansProvider
     * @return void
     */
    public function testDiffForHumansRelative($now, $date, $expected)
    {
        $this->assertSame($expected, $now->diffForHumans($date));
    }

    public function testDiffForHumansWithNow()
    {
        $this->wrapWithTestNow(function () {
            $this->assertSame('1 second ago', Chronos::now()->subSeconds(1)->diffForHumans());
            $this->assertSame('1 second from now', Chronos::now()->addSeconds(1)->diffForHumans());
        });
    }

    public function testDiffForHumansWithNowAbsolute()
    {
        $this->wrapWithTestNow(function () {
            $this->assertSame('1 second', Chronos::now()->subSeconds(1)->diffForHumans(null, true));
            $this->assertSame('1 second', Chronos::now()->addSeconds(1)->diffForHumans(null, true));
        });
    }

    public function testDiffForHumansWithoutDiff()
    {
        $this->wrapWithTestNow(function () {
            $this->assertSame('0 seconds ago', Chronos::now()->diffForHumans());
        });
    }

    public function testDiffForHumansWithoutDiffAbsolute()
    {
        $this->wrapWithTestNow(function () {
            $this->assertSame('0 seconds', Chronos::now()->diffForHumans(null, true));
        });
    }

    public function testDiffFormatterSameObject()
    {
        $formatter = Chronos::diffFormatter();
        $this->assertInstanceOf('Cake\Chronos\DifferenceFormatter', $formatter);

        $second = Chronos::diffFormatter();
        $this->assertSame($second, $formatter, 'Same object returned on multiple calls');
    }

    public function testDiffFormatterSetter()
    {
        $formatter = new \Cake\Chronos\DifferenceFormatter();
        $result = Chronos::diffFormatter($formatter);
        $this->assertSame($result, $formatter, 'Should return parameter');

        $second = Chronos::diffFormatter();
        $this->assertSame($second, $formatter, 'Same object returned later');
    }
}
