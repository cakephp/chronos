<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class IsTest extends TestCase
{
    public function testIsWeekdayTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2012, 1, 2)->isWeekday());
    }

    public function testIsWeekdayFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2012, 1, 1)->isWeekday());
    }

    public function testIsWeekendTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2012, 1, 1)->isWeekend());
    }

    public function testIsWeekendFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2012, 1, 2)->isWeekend());
    }

    public function testIsYesterdayTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->subDays(1)->isYesterday());
    }

    public function testIsYesterdayFalseWithToday(): void
    {
        $this->assertFalse(ChronosDate::now()->isYesterday());
    }

    public function testIsYesterdayFalseWith2Days(): void
    {
        $this->assertFalse(ChronosDate::now()->subDays(2)->isYesterday());
    }

    public function testIsTodayTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->isToday());
    }

    public function testIsTodayFalseWithYesterday(): void
    {
        $this->assertFalse(ChronosDate::now()->subDays(1)->isToday());
    }

    public function testIsTodayFalseWithTomorrow(): void
    {
        $this->assertFalse(ChronosDate::now()->addDays(1)->isToday());
    }

    public function isTodayFalseWithTimezone(): void
    {
        date_default_timezone_set('Pacific/Kiritimati');
        $samoaTimezone = new DateTimeZone('Pacific/Samoa');

        // Pacific/Samoa -11:00 is used intead of local timezone +14:00
        $this->assertFalse(ChronosDate::now()->isToday($samoaTimezone));
        $this->assertTrue(ChronosDate::now()->isToday('Pacific/Kiritimati'));
    }

    public function testIsTomorrowTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->addDays(1)->isTomorrow());
    }

    public function testIsTomorrowFalseWithToday(): void
    {
        $this->assertFalse(ChronosDate::now()->isTomorrow());
    }

    public function testIsTomorrowFalseWith2Days(): void
    {
        $this->assertFalse(Chronos::now()->addDays(2)->isTomorrow());
    }

    public function testIsNextWeekTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->addWeeks(1)->isNextWeek());
    }

    public function testIsLastWeekTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->subWeeks(1)->isLastWeek());
    }

    public function testIsNextWeekFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->addWeeks(2)->isNextWeek());

        Chronos::setTestNow('2017-W01');
        $time = new ChronosDate('2018-W02');
        $this->assertFalse($time->isNextWeek());
    }

    public function testIsLastWeekFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->subWeeks(2)->isLastWeek());

        Chronos::setTestNow('2018-W02');
        $time = new ChronosDate('2017-W01');
        $this->assertFalse($time->isLastWeek());
    }

    public function testIsNextMonthTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->addMonths(1)->isNextMonth());
    }

    public function testIsLastMonthTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->subMonths(1)->isLastMonth());
    }

    public function testIsNextMonthFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->addMonths(2)->isNextMonth());

        Chronos::setTestNow('2017-12-31');
        $time = new ChronosDate('2017-01-01');
        $this->assertFalse($time->isNextMonth());
    }

    public function testIsLastMonthFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->subMonths(2)->isLastMonth());

        Chronos::setTestNow('2017-01-01');
        $time = new ChronosDate('2017-12-31');
        $this->assertFalse($time->isLastMonth());
    }

    public function testIsNextYearTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->addYears(1)->isNextYear());
    }

    public function testIsLastYearTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->subYears(1)->isLastYear());
    }

    public function testIsNextYearFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->addYears(2)->isNextYear());
    }

    public function testIsLastYearFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->subYears(2)->isLastYear());
    }

    public function testIsFutureTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->addDays(1)->isFuture());
    }

    public function testIsFutureFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->isFuture());
    }

    public function testIsFutureFalseInThePast(): void
    {
        $this->assertFalse(ChronosDate::now()->subDays(1)->isFuture());
    }

    public function testIsPastTrue(): void
    {
        $this->assertTrue(ChronosDate::now()->subDays(1)->isPast());
    }

    public function testIsPastFalse(): void
    {
        $this->assertFalse(ChronosDate::now()->addDays(1)->isPast());
    }

    public function testIsLeapYearTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2016, 1, 1)->isLeapYear());
    }

    public function testIsLeapYearFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2014, 1, 1)->isLeapYear());
    }

    public function testIsSunday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 5, 31)->isSunday());
        $this->assertTrue(ChronosDate::create(2015, 6, 21)->isSunday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::SUNDAY)->isSunday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::SUNDAY)->isSunday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::SUNDAY)->isSunday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::MONDAY)->isSunday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::MONDAY)->isSunday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::MONDAY)->isSunday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::MONDAY)->isSunday());
    }

    public function testIsMonday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 1)->isMonday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::MONDAY)->isMonday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::MONDAY)->isMonday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::MONDAY)->isMonday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::TUESDAY)->isMonday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::TUESDAY)->isMonday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::TUESDAY)->isMonday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::TUESDAY)->isMonday());
    }

    public function testIsTuesday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 2)->isTuesday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::TUESDAY)->isTuesday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::TUESDAY)->isTuesday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::TUESDAY)->isTuesday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::WEDNESDAY)->isTuesday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::WEDNESDAY)->isTuesday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::WEDNESDAY)->isTuesday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::WEDNESDAY)->isTuesday());
    }

    public function testIsWednesday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 3)->isWednesday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::WEDNESDAY)->isWednesday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::WEDNESDAY)->isWednesday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::WEDNESDAY)->isWednesday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::THURSDAY)->isWednesday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::THURSDAY)->isWednesday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::THURSDAY)->isWednesday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::THURSDAY)->isWednesday());
    }

    public function testIsThursday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 4)->isThursday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::THURSDAY)->isThursday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::THURSDAY)->isThursday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::THURSDAY)->isThursday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::FRIDAY)->isThursday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::FRIDAY)->isThursday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::FRIDAY)->isThursday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::FRIDAY)->isThursday());
    }

    public function testIsFriday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 5)->isFriday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::FRIDAY)->isFriday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::FRIDAY)->isFriday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::FRIDAY)->isFriday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::SATURDAY)->isFriday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::SATURDAY)->isFriday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::SATURDAY)->isFriday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::SATURDAY)->isFriday());
    }

    public function testIsSaturday(): void
    {
        // True in the past past
        $this->assertTrue(ChronosDate::create(2015, 6, 6)->isSaturday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::SATURDAY)->isSaturday());

        // True in the future
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::SATURDAY)->isSaturday());
        $this->assertTrue(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::SATURDAY)->isSaturday());

        // False in the past
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subWeeks(1)->previous(Chronos::SUNDAY)->isSaturday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->subMonths(1)->previous(Chronos::SUNDAY)->isSaturday());

        // False in the future
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addWeeks(1)->previous(Chronos::SUNDAY)->isSaturday());
        $this->assertFalse(ChronosDate::parse(Chronos::now())->addMonths(1)->previous(Chronos::SUNDAY)->isSaturday());
    }

    public function testWasWithinLast(): void
    {
        $this->assertTrue((new Chronos('-1 day'))->wasWithinLast('1 day'));
        $this->assertTrue((new Chronos('-1 week'))->wasWithinLast('1 week'));
        $this->assertTrue((new Chronos('-1 year'))->wasWithinLast('1 year'));
        $this->assertTrue((new Chronos('-1 day'))->wasWithinLast('1 week'));
        $this->assertTrue((new Chronos('-1 week'))->wasWithinLast('2 week'));
        $this->assertTrue((new Chronos('-1 month'))->wasWithinLast('13 month'));
        $this->assertFalse((new Chronos('-1 year'))->wasWithinLast('0 year'));
        $this->assertFalse((new Chronos('-1 weeks'))->wasWithinLast('1 day'));
    }

    public function testIsWithinNext(): void
    {
        $this->assertFalse((new Chronos('-1 day'))->isWithinNext('1 day'));
        $this->assertFalse((new Chronos('-1 week'))->isWithinNext('1 week'));
        $this->assertFalse((new Chronos('-1 year'))->isWithinNext('1 year'));
        $this->assertFalse((new Chronos('-1 day'))->isWithinNext('1 week'));
        $this->assertFalse((new Chronos('-1 week'))->isWithinNext('2 week'));
        $this->assertFalse((new Chronos('-1 month'))->isWithinNext('13 month'));
        $this->assertTrue((new Chronos('+1 day'))->isWithinNext('1 day'));
        $this->assertTrue((new Chronos('+1 week'))->isWithinNext('7 day'));
        $this->assertTrue((new Chronos('+1 month'))->isWithinNext('1 month'));
    }

    #[TestWith([1, true, false])]
    #[TestWith([2, true, false])]
    #[TestWith([3, true, false])]
    #[TestWith([4, true, false])]
    #[TestWith([5, true, false])]
    #[TestWith([6, true, false])]
    #[TestWith([7, false, true])]
    #[TestWith([8, false, true])]
    #[TestWith([9, false, true])]
    #[TestWith([10, false, true])]
    #[TestWith([11, false, true])]
    #[TestWith([12, false, true])]
    public function testIsFirstOrSecondHalfOfYear(int $month, bool $isFirstHalfOfYear, bool $isSecondHalfOfYear): void
    {
        $d = Chronos::createFromDate(2023, $month, 1);
        $this->assertSame($isFirstHalfOfYear, $d->isFirstHalf());
        $this->assertSame($isSecondHalfOfYear, $d->isSecondHalf());
    }
}
