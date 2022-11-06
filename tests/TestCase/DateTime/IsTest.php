<?php
declare(strict_types=1);

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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Test\TestCase\TestCase;

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
        $this->assertTrue($class::now()->subDays(1)->isYesterday());
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
        $this->assertFalse($class::now()->subDays(1)->endOfDay()->isToday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTodayFalseWithTomorrow($class)
    {
        $this->assertFalse($class::now()->addDays(1)->startOfDay()->isToday());
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
        $this->assertTrue($class::now()->addDays(1)->isTomorrow());
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
        $this->assertTrue($class::now()->addWeeks(1)->isNextWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastWeekTrue($class)
    {
        $this->assertTrue($class::now()->subWeeks(1)->isLastWeek());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextWeekFalse($class)
    {
        $this->assertFalse($class::now()->addWeeks(2)->isNextWeek());

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
        $this->assertFalse($class::now()->subWeeks(2)->isLastWeek());

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
        $this->assertTrue($class::now()->addMonths(1)->isNextMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastMonthTrue($class)
    {
        $this->assertTrue($class::now()->subMonths(1)->isLastMonth());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextMonthFalse($class)
    {
        $this->assertFalse($class::now()->addMonths(2)->isNextMonth());

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
        $this->assertFalse($class::now()->subMonths(2)->isLastMonth());

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
        $this->assertTrue($class::now()->addYears(1)->isNextYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastYearTrue($class)
    {
        $this->assertTrue($class::now()->subYears(1)->isLastYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsNextYearFalse($class)
    {
        $this->assertFalse($class::now()->addYears(2)->isNextYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsLastYearFalse($class)
    {
        $this->assertFalse($class::now()->subYears(2)->isLastYear());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFutureTrue($class)
    {
        $this->assertTrue($class::now()->addSeconds(1)->isFuture());
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
        $this->assertFalse($class::now()->subSeconds(1)->isFuture());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsPastTrue($class)
    {
        $this->assertTrue($class::now()->subSeconds(1)->isPast());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsPastFalse($class)
    {
        $this->assertFalse($class::now()->addSeconds(1)->isPast());
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
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::SUNDAY)->isSunday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::SUNDAY)->isSunday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::SUNDAY)->isSunday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::MONDAY)->isSunday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::MONDAY)->isSunday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::MONDAY)->isSunday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::MONDAY)->isSunday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsMonday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 1)->isMonday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::MONDAY)->isMonday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::MONDAY)->isMonday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::MONDAY)->isMonday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::TUESDAY)->isMonday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::TUESDAY)->isMonday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTuesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 2)->isTuesday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::TUESDAY)->isTuesday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::TUESDAY)->isTuesday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::TUESDAY)->isTuesday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::WEDNESDAY)->isTuesday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::WEDNESDAY)->isTuesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWednesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 3)->isWednesday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::WEDNESDAY)->isWednesday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::WEDNESDAY)->isWednesday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::WEDNESDAY)->isWednesday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::THURSDAY)->isWednesday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::THURSDAY)->isWednesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThursday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 4)->isThursday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::THURSDAY)->isThursday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::THURSDAY)->isThursday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::THURSDAY)->isThursday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::FRIDAY)->isThursday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::FRIDAY)->isThursday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFriday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 5)->isFriday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::FRIDAY)->isFriday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::FRIDAY)->isFriday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::FRIDAY)->isFriday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::SATURDAY)->isFriday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::SATURDAY)->isFriday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSaturday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 6)->isSaturday());
        $this->assertTrue($class::now()->subWeeks(1)->previous($class::SATURDAY)->isSaturday());

        // True in the future
        $this->assertTrue($class::now()->addWeeks(1)->previous($class::SATURDAY)->isSaturday());
        $this->assertTrue($class::now()->addMonths(1)->previous($class::SATURDAY)->isSaturday());

        // False in the past
        $this->assertFalse($class::now()->subWeeks(1)->previous($class::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->subMonths(1)->previous($class::SUNDAY)->isSaturday());

        // False in the future
        $this->assertFalse($class::now()->addWeeks(1)->previous($class::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->addMonths(1)->previous($class::SUNDAY)->isSaturday());
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
