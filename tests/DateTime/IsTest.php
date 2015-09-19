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

class IsTest extends TestFixture
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
        $this->assertFalse($class::now()->isPast());
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
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::SUNDAY)->isSunday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::SUNDAY)->isSunday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::SUNDAY)->isSunday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::MONDAY)->isSunday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::MONDAY)->isSunday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::MONDAY)->isSunday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::MONDAY)->isSunday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsMonday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 1)->isMonday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::MONDAY)->isMonday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::MONDAY)->isMonday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::MONDAY)->isMonday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::TUESDAY)->isMonday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::TUESDAY)->isMonday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::TUESDAY)->isMonday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsTuesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 2)->isTuesday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::TUESDAY)->isTuesday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::TUESDAY)->isTuesday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::TUESDAY)->isTuesday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::WEDNESDAY)->isTuesday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::WEDNESDAY)->isTuesday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::WEDNESDAY)->isTuesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsWednesday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 3)->isWednesday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::WEDNESDAY)->isWednesday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::WEDNESDAY)->isWednesday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::WEDNESDAY)->isWednesday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::THURSDAY)->isWednesday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::THURSDAY)->isWednesday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::THURSDAY)->isWednesday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsThursday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 4)->isThursday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::THURSDAY)->isThursday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::THURSDAY)->isThursday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::THURSDAY)->isThursday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::FRIDAY)->isThursday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::FRIDAY)->isThursday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::FRIDAY)->isThursday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsFriday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 5)->isFriday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::FRIDAY)->isFriday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::FRIDAY)->isFriday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::FRIDAY)->isFriday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::SATURDAY)->isFriday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::SATURDAY)->isFriday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::SATURDAY)->isFriday());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsSaturday($class)
    {
        // True in the past past
        $this->assertTrue($class::createFromDate(2015, 6, 6)->isSaturday());
        $this->assertTrue($class::now()->subWeek()->previous(Carbon::SATURDAY)->isSaturday());

        // True in the future
        $this->assertTrue($class::now()->addWeek()->previous(Carbon::SATURDAY)->isSaturday());
        $this->assertTrue($class::now()->addMonth()->previous(Carbon::SATURDAY)->isSaturday());

        // False in the past
        $this->assertFalse($class::now()->subWeek()->previous(Carbon::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->subMonth()->previous(Carbon::SUNDAY)->isSaturday());

        // False in the future
        $this->assertFalse($class::now()->addWeek()->previous(Carbon::SUNDAY)->isSaturday());
        $this->assertFalse($class::now()->addMonth()->previous(Carbon::SUNDAY)->isSaturday());
    }
}
