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
use Cake\Chronos\Test\TestCase\TestCase;
use Closure;
use DateTimeZone;

class DiffTest extends TestCase
{
    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        parent::wrapWithTestNow($func, $dt ?? Chronos::create(2012, 1, 1));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInYearsPositive($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(1, $dt->diffInYears($other->addYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInYearsNegativeWithSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(-1, $dt->diffInYears($other->subYears(1), false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInYearsNegativeNoSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(1, $dt->diffInYears($other->subYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInYearsVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(1, $class::now()->subYears(1)->diffInYears());
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInYearsEnsureIsTruncated($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(1, $dt->diffInYears($other->addYears(1)->addMonths(7)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInMonthsPositive($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(13, $dt->diffInMonths($other->addYears(1)->addMonths(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInMonthsNegativeWithSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(-11, $dt->diffInMonths($other->subYears(1)->addMonths(1), false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInMonthsNegativeNoSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(11, $dt->diffInMonths($other->subYears(1)->addMonths(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInMonthsVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(12, $class::today()->subYears(1)->diffInMonths());
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInMonthsEnsureIsTruncated($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(1, $dt->diffInMonths($other->addMonths(1)->addDays(16)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysPositive($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(366, $dt->diffInDays($other->addYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysNegativeWithSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(-365, $dt->diffInDays($other->subYears(1), false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysNegativeNoSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(365, $dt->diffInDays($other->subYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(7, $class::today()->subWeeks(1)->diffInDays());
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysFilteredPositiveWithSecondObject($class)
    {
        $dt1 = $class::create(2000, 1, 1);
        $dt2 = $class::create(2000, 1, 31);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeNoSignWithSecondObject($class)
    {
        $dt1 = $class::create(2000, 1, 31);
        $dt2 = $class::create(2000, 1, 1);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysFilteredNegativeWithSignWithSecondObject($class)
    {
        $dt1 = $class::create(2000, 1, 31);
        $dt2 = $class::create(2000, 1, 1);

        $this->assertSame(-5, $dt1->diffInDaysFiltered(function ($date) use ($class) {
            return $date->dayOfWeek === $class::SUNDAY;
        }, $dt2, false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffFilteredNegativeNoSignWithSecondObject($class)
    {
        $dt1 = $class::create(2006, 1, 31);
        $dt2 = $class::create(2000, 1, 1);

        $interval = Chronos::createInterval(1);
        $this->assertSame(7, $dt1->diffFiltered($interval, function ($date) {
            return $date->month === 1;
        }, $dt2));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffFilteredNegativeWithSignWithMutated($class)
    {
        $dt = $class::create(2000, 1, 31);
        $interval = Chronos::createInterval(0, 0, 1);
        $this->assertSame(-4, $dt->diffFiltered($interval, function ($date) {
            return $date->month === 12;
        }, (clone $dt)->subMonths(3), false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffFilteredNegativeWithSignWithSecondObject($class)
    {
        $dt1 = $class::create(2001, 1, 31);
        $dt2 = $class::create(1999, 1, 1);

        $interval = Chronos::createInterval(0, 1);
        $this->assertSame(-12, $dt1->diffFiltered($interval, function ($date) {
            return $date->year === 2000;
        }, $dt2, false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testBug188DiffWithSameDates($class)
    {
        $start = $class::create(2014, 10, 8);
        $end = clone $start;

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testBug188DiffWithSameDates1DayApart($class)
    {
        $start = $class::create(2014, 10, 8);
        $end = (clone $start)->addDays(1);

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(1, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInDaysTwoWeeks($class)
    {
        $start = $class::create(2014, 10, 8);
        for ($i = 1; $i <= 14; $i++) {
            $end = (clone $start)->addDays($i);
            $this->assertSame($i, $start->diffInDays($end));
        }
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testBug188DiffWithDatesOnTheWeekend($class)
    {
        $start = $class::create(2014, 1, 1);
        $start = $start->next($class::SATURDAY);
        $end = (clone $start)->addDays(1);

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInWeeksPositive($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(52, $dt->diffInWeeks($other->addYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInWeeksNegativeWithSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(-52, $dt->diffInWeeks($other->subYears(1), false));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInWeeksNegativeNoSign($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(52, $dt->diffInWeeks($other->subYears(1)));
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInWeeksVsDefaultNow($class)
    {
        $this->wrapWithTestNow(function () use ($class) {
            $this->assertSame(1, $class::now()->subWeeks(1)->diffInWeeks());
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testDiffInWeeksEnsureIsTruncated($class)
    {
        $dt = $class::create(2000, 1, 1);
        $other = clone $dt;
        $this->assertSame(0, $dt->diffInWeeks($other->addWeeks(1)->subDays(1)));
    }

    public function diffForHumansProvider()
    {
        $now = Chronos::parse('2020-01-04 10:01:01');

        return [
            [$now, $now->addYears(11), '11 years before'],
            [$now, $now->addYears(1), '1 year before'],
            [$now, $now->addMonths(11), '11 months before'],
            [$now, $now->addMonths(2), '2 months before'],
            [$now, $now->addWeeks(8), '8 weeks before'],
            [$now, $now->addDays(21), '3 weeks before'],
            [$now, $now->addDays(20), '20 days before'],
            [$now, $now->addDays(8), '8 days before'],
            [$now, $now->addDays(1), '1 day before'],
            [$now, $now->addDays(6), '6 days before'],

            [$now, $now->subYears(11), '11 years after'],
            [$now, $now->subYears(1), '1 year after'],
            [$now, $now->subMonths(11), '11 months after'],
            [$now, $now->subMonths(2), '2 months after'],
            [$now, $now->subWeeks(8), '8 weeks after'],
            [$now, $now->subDays(21), '3 weeks after'],
            [$now, $now->subDays(20), '20 days after'],
            [$now, $now->subDays(8), '8 days after'],
            [$now, $now->subDays(1), '1 day after'],
            [$now, $now->subDays(6), '6 days after'],
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
