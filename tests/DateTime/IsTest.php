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

class IsTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWeekdayTrue($class)
    {
        $this->assertTrue($class::createFromDate(2012, 1, 2)->isWeekday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWeekdayFalse($class)
    {
        $this->assertFalse($class::createFromDate(2012, 1, 1)->isWeekday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWeekendTrue($class)
    {
        $this->assertTrue($class::createFromDate(2012, 1, 1)->isWeekend());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWeekendFalse($class)
    {
        $this->assertFalse($class::createFromDate(2012, 1, 2)->isWeekend());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsYesterdayTrue($class)
    {
        $this->assertTrue($class::now()->subDay()->isYesterday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsYesterdayFalseWithToday($class)
    {
        $this->assertFalse($class::now()->endOfDay()->isYesterday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsYesterdayFalseWith2Days($class)
    {
        $this->assertFalse($class::now()->subDays(2)->startOfDay()->isYesterday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTodayTrue($class)
    {
        $this->assertTrue($class::now()->isToday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTodayFalseWithYesterday($class)
    {
        $this->assertFalse($class::now()->subDay()->endOfDay()->isToday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTodayFalseWithTomorrow($class)
    {
        $this->assertFalse($class::now()->addDay()->startOfDay()->isToday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTodayWithTimezone($class)
    {
        $this->assertTrue($class::now('Asia/Tokyo')->isToday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTomorrowTrue($class)
    {
        $this->assertTrue($class::now()->addDay()->isTomorrow());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTomorrowFalseWithToday($class)
    {
        $this->assertFalse($class::now()->endOfDay()->isTomorrow());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTomorrowFalseWith2Days($class)
    {
        $this->assertFalse($class::now()->addDays(2)->startOfDay()->isTomorrow());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextWeekTrue($class)
    {
        $this->assertTrue($class::now()->addWeek()->isNextWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastWeekTrue($class)
    {
        $this->assertTrue($class::now()->subWeek()->isLastWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextWeekFalse($class)
    {
        $this->assertFalse($class::now()->addWeek(2)->isNextWeek());

        $class::setTestNow('2017-W01');
        $time = new $class('2018-W02');
        $this->assertFalse($time->isNextWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastWeekFalse($class)
    {
        $this->assertFalse($class::now()->subWeek(2)->isLastWeek());

        $class::setTestNow('2018-W02');
        $time = new $class('2017-W01');
        $this->assertFalse($time->isLastWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextMonthTrue($class)
    {
        $this->assertTrue($class::now()->addMonth()->isNextMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastMonthTrue($class)
    {
        $this->assertTrue($class::now()->subMonth()->isLastMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextMonthFalse($class)
    {
        $this->assertFalse($class::now()->addMonth(2)->isNextMonth());

        $class::setTestNow('2017-12-31');
        $time = new $class('2017-01-01');
        $this->assertFalse($time->isNextMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastMonthFalse($class)
    {
        $this->assertFalse($class::now()->subMonth(2)->isLastMonth());

        $class::setTestNow('2017-01-01');
        $time = new $class('2017-12-31');
        $this->assertFalse($time->isLastMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextYearTrue($class)
    {
        $this->assertTrue($class::now()->addYear()->isNextYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastYearTrue($class)
    {
        $this->assertTrue($class::now()->subYear()->isLastYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextYearFalse($class)
    {
        $this->assertFalse($class::now()->addYear(2)->isNextYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastYearFalse($class)
    {
        $this->assertFalse($class::now()->subYear(2)->isLastYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFutureTrue($class)
    {
        $this->assertTrue($class::now()->addSecond()->isFuture());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFutureFalse($class)
    {
        $this->assertFalse($class::now()->isFuture());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFutureFalseInThePast($class)
    {
        $this->assertFalse($class::now()->subSecond()->isFuture());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsPastTrue($class)
    {
        $this->assertTrue($class::now()->subSecond()->isPast());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsPastFalse($class)
    {
        $this->assertFalse($class::now()->addSecond()->isPast());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLeapYearTrue($class)
    {
        $this->assertTrue($class::createFromDate(2016, 1, 1)->isLeapYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLeapYearFalse($class)
    {
        $this->assertFalse($class::createFromDate(2014, 1, 1)->isLeapYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSameDayTrue($class)
    {
        $current = $class::createFromDate(2012, 1, 2);
        $this->assertTrue($current->isSameDay($class::createFromDate(2012, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSameDayFalse($class)
    {
        $current = $class::createFromDate(2012, 1, 2);
        $this->assertFalse($current->isSameDay($class::createFromDate(2012, 1, 3)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSunday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 5, 31)->isSunday());
        $this->assertTrue($class::createFromDate(2015, 6, 21)->isSunday());
        $this->assertTrue($class::now()->subWeek()->previous($class::SUNDAY)->isSunday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::SUNDAY)->isSunday());
        $this->assertTrue($class::now()->addMonth()->previous($class::SUNDAY)->isSunday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::MONDAY)->isSunday());
        $this->assertFalse($class::now()->subMonth()->previous($class::MONDAY)->isSunday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::MONDAY)->isSunday());
        $this->assertFalse($class::now()->addMonth()->previous($class::MONDAY)->isSunday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsMonday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 1)->isMonday());
        $this->assertTrue($class::now()->subWeek()->previous($class::MONDAY)->isMonday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::MONDAY)->isMonday());
        $this->assertTrue($class::now()->addMonth()->previous($class::MONDAY)->isMonday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->subMonth()->previous($class::TUESDAY)->isMonday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->addMonth()->previous($class::TUESDAY)->isMonday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTuesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 2)->isTuesday());
        $this->assertTrue($class::now()->subWeek()->previous($class::TUESDAY)->isTuesday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::TUESDAY)->isTuesday());
        $this->assertTrue($class::now()->addMonth()->previous($class::TUESDAY)->isTuesday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->subMonth()->previous($class::WEDNESDAY)->isTuesday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->addMonth()->previous($class::WEDNESDAY)->isTuesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWednesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 3)->isWednesday());
        $this->assertTrue($class::now()->subWeek()->previous($class::WEDNESDAY)->isWednesday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::WEDNESDAY)->isWednesday());
        $this->assertTrue($class::now()->addMonth()->previous($class::WEDNESDAY)->isWednesday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->subMonth()->previous($class::THURSDAY)->isWednesday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->addMonth()->previous($class::THURSDAY)->isWednesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThursday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 4)->isThursday());
        $this->assertTrue($class::now()->subWeek()->previous($class::THURSDAY)->isThursday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::THURSDAY)->isThursday());
        $this->assertTrue($class::now()->addMonth()->previous($class::THURSDAY)->isThursday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->subMonth()->previous($class::FRIDAY)->isThursday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->addMonth()->previous($class::FRIDAY)->isThursday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFriday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 5)->isFriday());
        $this->assertTrue($class::now()->subWeek()->previous($class::FRIDAY)->isFriday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::FRIDAY)->isFriday());
        $this->assertTrue($class::now()->addMonth()->previous($class::FRIDAY)->isFriday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->subMonth()->previous($class::SATURDAY)->isFriday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->addMonth()->previous($class::SATURDAY)->isFriday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSaturday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 6)->isSaturday());
        $this->assertTrue($class::now()->subWeek()->previous($class::SATURDAY)->isSaturday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous($class::SATURDAY)->isSaturday());
        $this->assertTrue($class::now()->addMonth()->previous($class::SATURDAY)->isSaturday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous($class::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->subMonth()->previous($class::SUNDAY)->isSaturday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous($class::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->addMonth()->previous($class::SUNDAY)->isSaturday());
    }

    /**
     * testIsThisWeek method
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThisWeek($class)
    {
        $time = new $class('this sunday');
        $this->assertTrue($time->isThisWeek());

        $time = $time->modify('-1 day');
        $this->assertTrue($time->isThisWeek());

        $time = $time->modify('-6 days');
        $this->assertFalse($time->isThisWeek());

        $time = new $class();
        $time = $time->year($time->year - 1);
        $this->assertFalse($time->isThisWeek());
    }

    /**
     * testIsThisMonth method
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThisMonth($class)
    {
        $time = new $class();
        $this->assertTrue($time->isThisMonth());

        $time = $time->year($time->year + 1);
        $this->assertFalse($time->isThisMonth());

        $time = new $class();
        $this->assertFalse($time->modify('next month')->isThisMonth());
    }

    /**
     * testIsThisYear method
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThisYear($class)
    {
        $time = new $class();
        $this->assertTrue($time->isThisYear());

        $time = $time->year($time->year + 1);
        $this->assertFalse($time->isThisYear());
    }

    /**
     * testWasWithinLast method
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWasWithinLast($class)
    {
        $this->assertTrue((new $class('-1 day'))->wasWithinLast('1 day'));
        $this->assertTrue((new $class('-1 week'))->wasWithinLast('1 week'));
        $this->assertTrue((new $class('-1 year'))->wasWithinLast('1 year'));
        $this->assertTrue((new $class('-1 second'))->wasWithinLast('1 second'));
        $this->assertTrue((new $class('-1 day'))->wasWithinLast('1 week'));
        $this->assertTrue((new $class('-1 week'))->wasWithinLast('2 week'));
        $this->assertTrue((new $class('-1 second'))->wasWithinLast('10 minutes'));
        $this->assertTrue((new $class('-1 month'))->wasWithinLast('13 month'));
        $this->assertTrue((new $class('-1 seconds'))->wasWithinLast('1 hour'));
        $this->assertFalse((new $class('-1 year'))->wasWithinLast('1 second'));
        $this->assertFalse((new $class('-1 year'))->wasWithinLast('0 year'));
        $this->assertFalse((new $class('-1 weeks'))->wasWithinLast('1 day'));
    }

    /**
     * testWasWithinLast method
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWithinNext($class)
    {
        $this->assertFalse((new $class('-1 day'))->isWithinNext('1 day'));
        $this->assertFalse((new $class('-1 week'))->isWithinNext('1 week'));
        $this->assertFalse((new $class('-1 year'))->isWithinNext('1 year'));
        $this->assertFalse((new $class('-1 second'))->isWithinNext('1 second'));
        $this->assertFalse((new $class('-1 day'))->isWithinNext('1 week'));
        $this->assertFalse((new $class('-1 week'))->isWithinNext('2 week'));
        $this->assertFalse((new $class('-1 second'))->isWithinNext('10 minutes'));
        $this->assertFalse((new $class('-1 month'))->isWithinNext('13 month'));
        $this->assertFalse((new $class('-1 seconds'))->isWithinNext('1 hour'));
        $this->assertTrue((new $class('+1 day'))->isWithinNext('1 day'));
        $this->assertTrue((new $class('+1 week'))->isWithinNext('7 day'));
        $this->assertTrue((new $class('+1 second'))->isWithinNext('1 minute'));
        $this->assertTrue((new $class('+1 month'))->isWithinNext('1 month'));
    }
}
