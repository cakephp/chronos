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

use Cake\Chronos\Chronos;
use Cake\Chronos\Test\TestCase\TestCase;

class ComparisonTest extends TestCase
{
    public function testGetSetWeekendDays()
    {
        $expected = [Chronos::SATURDAY, Chronos::SUNDAY];
        $this->assertSame($expected, Chronos::getWeekendDays());

        $replace = [Chronos::SUNDAY];
        Chronos::setWeekendDays($replace);
        $this->assertSame($replace, Chronos::getWeekendDays());

        Chronos::setWeekendDays($expected);
    }

    public function testEqualToTrue()
    {
        $this->assertTrue(Chronos::create(2000, 1, 1, 0, 0, 0)->equals(Chronos::create(2000, 1, 1, 0, 0, 0)));
    }

    public function testEqualToFalse()
    {
        $this->assertFalse(Chronos::create(2000, 1, 1, 0, 0, 0)->equals(Chronos::create(2000, 1, 2, 0, 0, 0)));
    }

    public function testEqualWithTimezoneTrue()
    {
        $this->assertTrue(Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto')->equals(Chronos::create(
            2000,
            1,
            1,
            9,
            0,
            0,
            0,
            'America/Vancouver'
        )));
    }

    public function testEqualWithTimezoneFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1, 'America/Toronto')->equals(Chronos::createFromDate(
            2000,
            1,
            1,
            'America/Vancouver'
        )));
    }

    public function testNotEqualToTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1)->notEquals(Chronos::createFromDate(2000, 1, 2)));
    }

    public function testNotEqualToFalse()
    {
        $this->assertFalse(Chronos::create(2000, 1, 1, 0, 0, 0)->notEquals(Chronos::create(2000, 1, 1, 0, 0, 0)));
    }

    public function testNotEqualWithTimezone()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1, 'America/Toronto')->notEquals(Chronos::createFromDate(
            2000,
            1,
            1,
            'America/Vancouver'
        )));
    }

    public function testGreaterThanTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1)->greaterThan(Chronos::createFromDate(1999, 12, 31)));
    }

    public function testGreaterThanFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->greaterThan(Chronos::createFromDate(2000, 1, 2)));
    }

    public function testGreaterThanWithTimezoneTrue()
    {
        $dt1 = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $dt2 = Chronos::create(2000, 1, 1, 8, 59, 59, 0, 'America/Vancouver');
        $this->assertTrue($dt1->greaterThan($dt2));
    }

    public function testGreaterThanWithTimezoneFalse()
    {
        $dt1 = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $dt2 = Chronos::create(2000, 1, 1, 9, 0, 1, 0, 'America/Vancouver');
        $this->assertFalse($dt1->greaterThan($dt2));
        $this->assertFalse($dt1->greaterThan($dt2));
    }

    public function testGreaterThanOrEqualTrue()
    {
        $this->assertTrue(Chronos::create(2000, 1, 1)->greaterThanOrEquals(Chronos::createFromDate(1999, 12, 31)));
    }

    public function testGreaterThanOrEqualTrueEqual()
    {
        $this->assertTrue(Chronos::create(2000, 1, 1, 0, 0, 0)->greaterThanOrEquals(Chronos::create(2000, 1, 1, 0, 0, 0)));
    }

    public function testGreaterThanOrEqualFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->greaterThanOrEquals(Chronos::createFromDate(2000, 1, 2)));
    }

    public function testLessThanTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1)->lessThan(Chronos::createFromDate(2000, 1, 2)));
    }

    public function testLessThanFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->lessThanOrEquals(Chronos::createFromDate(1999, 12, 31)));
    }

    public function testLessThanOrEqualTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1)->lessThanOrEquals(Chronos::createFromDate(2000, 1, 2)));
    }

    public function testLessThanOrEqualTrueEqual()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 1)->lessThanOrEquals(Chronos::createFromDate(2000, 1, 1)));
    }

    public function testLessThanOrEqualFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->lessThanOrEquals(Chronos::createFromDate(1999, 12, 31)));
    }

    public function testBetweenEqualTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 15)->between(
            Chronos::createFromDate(2000, 1, 1),
            Chronos::createFromDate(2000, 1, 31),
            true
        ));
    }

    public function testBetweenNotEqualTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 15)->between(
            Chronos::createFromDate(2000, 1, 1),
            Chronos::createFromDate(2000, 1, 31),
            false
        ));
    }

    public function testBetweenEqualFalse()
    {
        $this->assertFalse(Chronos::createFromDate(1999, 12, 31)->between(
            Chronos::createFromDate(2000, 1, 1),
            Chronos::createFromDate(2000, 1, 31),
            true
        ));
    }

    public function testBetweenNotEqualFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->between(
            Chronos::createFromDate(2000, 1, 1),
            Chronos::createFromDate(2000, 1, 31),
            false
        ));
    }

    public function testBetweenEqualSwitchTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 15)->between(
            Chronos::createFromDate(2000, 1, 31),
            Chronos::createFromDate(2000, 1, 1),
            true
        ));
    }

    public function testBetweenNotEqualSwitchTrue()
    {
        $this->assertTrue(Chronos::createFromDate(2000, 1, 15)->between(
            Chronos::createFromDate(2000, 1, 31),
            Chronos::createFromDate(2000, 1, 1),
            false
        ));
    }

    public function testBetweenEqualSwitchFalse()
    {
        $this->assertFalse(Chronos::createFromDate(1999, 12, 31)->between(
            Chronos::createFromDate(2000, 1, 31),
            Chronos::createFromDate(2000, 1, 1),
            true
        ));
    }

    public function testBetweenNotEqualSwitchFalse()
    {
        $this->assertFalse(Chronos::createFromDate(2000, 1, 1)->between(
            Chronos::createFromDate(2000, 1, 31),
            Chronos::createFromDate(2000, 1, 1),
            false
        ));
    }

    public function testMinIsFluid()
    {
        $dt = Chronos::now();
        $this->assertTrue($dt->min() instanceof Chronos);
    }

    public function testMinWithNow()
    {
        $dt = Chronos::create(2012, 1, 1, 0, 0, 0)->min();
        $this->assertDateTime($dt, 2012, 1, 1, 0, 0, 0);
    }

    public function testMinWithInstance()
    {
        $dt1 = Chronos::create(2013, 12, 31, 23, 59, 59);
        $dt2 = Chronos::create(2012, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertDateTime($dt2, 2012, 1, 1, 0, 0, 0);
    }

    public function testMaxIsFluid()
    {
        $dt = Chronos::now();
        $this->assertTrue($dt->max() instanceof Chronos);
    }

    public function testMaxWithNow()
    {
        $dt = Chronos::create(2099, 12, 31, 23, 59, 59)->max();
        $this->assertDateTime($dt, 2099, 12, 31, 23, 59, 59);
    }

    public function testMaxWithInstance()
    {
        $dt1 = Chronos::create(2012, 1, 1, 0, 0, 0);
        $dt2 = Chronos::create(2099, 12, 31, 23, 59, 59)->max($dt1);
        $this->assertDateTime($dt2, 2099, 12, 31, 23, 59, 59);
    }

    public function testIsBirthday()
    {
        $dt = Chronos::now();
        $aBirthday = $dt->subYears(1);
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDays(1);
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = Chronos::createFromDate(1987, 4, 23);
        $dt2 = Chronos::createFromDate(2014, 9, 26);
        $dt3 = Chronos::createFromDate(2014, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));
    }

    public function testClosest()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testClosestWithEquals()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testFarthest()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }

    public function testFarthestWithEquals()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }
}
