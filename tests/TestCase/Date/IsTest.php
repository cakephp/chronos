<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

class IsTest extends TestCase
{
    public function testIsWeekdayTrue()
    {
        $this->assertTrue(ChronosDate::create(2012, 1, 2)->isWeekday());
    }

    public function testIsWeekdayFalse()
    {
        $this->assertFalse(ChronosDate::create(2012, 1, 1)->isWeekday());
    }

    public function testIsWeekendTrue()
    {
        $this->assertTrue(ChronosDate::create(2012, 1, 1)->isWeekend());
    }

    public function testIsWeekendFalse()
    {
        $this->assertFalse(ChronosDate::create(2012, 1, 2)->isWeekend());
    }

    public function testIsLeapYearTrue()
    {
        $this->assertTrue(ChronosDate::create(2016, 1, 1)->isLeapYear());
    }

    public function testIsLeapYearFalse()
    {
        $this->assertFalse(ChronosDate::create(2014, 1, 1)->isLeapYear());
    }

    public function testIsSunday()
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

    public function testIsMonday()
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

    public function testIsTuesday()
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

    public function testIsWednesday()
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

    public function testIsThursday()
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

    public function testIsFriday()
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

    public function testIsSaturday()
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

    public function testWasWithinLast()
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

    public function testIsWithinNext()
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
}
