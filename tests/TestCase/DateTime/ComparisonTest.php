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

use Cake\Chronos\ChronosInterface;
use Cake\Chronos\Test\TestCase\TestCase;

class ComparisonTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGetSetWeekendDays($class)
    {
        $expected = [ChronosInterface::SATURDAY, ChronosInterface::SUNDAY];
        $this->assertSame($expected, $class::getWeekendDays());

        $replace = [ChronosInterface::SUNDAY];
        $class::setWeekendDays($replace);
        $this->assertSame($replace, $class::getWeekendDays());

        $class::setWeekendDays($expected);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualToTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::create(2000, 1, 1, 0, 0, 0)->eq($class::create(2000, 1, 1, 0, 0, 0)));
        });
        $this->assertTrue($class::create(2000, 1, 1, 0, 0, 0)->equals($class::create(2000, 1, 1, 0, 0, 0)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualToFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::create(2000, 1, 1, 0, 0, 0)->eq($class::create(2000, 1, 2, 0, 0, 0)));
        });
        $this->assertFalse($class::create(2000, 1, 1, 0, 0, 0)->equals($class::create(2000, 1, 2, 0, 0, 0)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualWithTimezoneTrue($class)
    {
        $compare = $class::create(2000, 1, 1, 9, 0, 0, 0, 'America/Vancouver');
        $this->deprecated(function () use ($class, $compare) {
            $this->assertTrue($class::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto')->eq($compare));
        });
        $this->assertTrue($class::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto')->equals($compare));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualWithTimezoneFalse($class)
    {
        $compare = $class::createFromDate(2000, 1, 1, 'America/Vancouver');
        $this->deprecated(function () use ($class, $compare) {
            $this->assertFalse($class::createFromDate(2000, 1, 1, 'America/Toronto')->eq($compare));
        });
        $this->assertFalse($class::createFromDate(2000, 1, 1, 'America/Toronto')->equals($compare));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualToTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::createFromDate(2000, 1, 1)->ne($class::createFromDate(2000, 1, 2)));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1)->notEquals($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualToFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::create(2000, 1, 1, 0, 0, 0)->ne($class::create(2000, 1, 1, 0, 0, 0)));
        });
        $this->assertFalse($class::create(2000, 1, 1, 0, 0, 0)->notEquals($class::create(2000, 1, 1, 0, 0, 0)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualWithTimezone($class)
    {
        $compare = $class::createFromDate(2000, 1, 1, 'America/Vancouver');
        $this->deprecated(function () use ($class, $compare) {
            $this->assertTrue($class::createFromDate(2000, 1, 1, 'America/Toronto')->ne($compare));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1, 'America/Toronto')->notEquals($compare));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::createFromDate(2000, 1, 1)->gt($class::createFromDate(1999, 12, 31)));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1)->greaterThan($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::createFromDate(2000, 1, 1)->gt($class::createFromDate(2000, 1, 2)));
        });
        $this->assertFalse($class::createFromDate(2000, 1, 1)->greaterThan($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanWithTimezoneTrue($class)
    {
        $dt1 = $class::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $dt2 = $class::create(2000, 1, 1, 8, 59, 59, 0, 'America/Vancouver');
        $this->deprecated(function () use ($dt1, $dt2) {
            $this->assertTrue($dt1->gt($dt2));
        });
        $this->assertTrue($dt1->greaterThan($dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanWithTimezoneFalse($class)
    {
        $dt1 = $class::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $dt2 = $class::create(2000, 1, 1, 9, 0, 1, 0, 'America/Vancouver');
        $this->deprecated(function () use ($dt1, $dt2) {
            $this->assertFalse($dt1->gt($dt2));
        });
        $this->assertFalse($dt1->greaterThan($dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::create(2000, 1, 1)->gte($class::createFromDate(1999, 12, 31)));
        });
        $this->assertTrue($class::create(2000, 1, 1)->greaterThanOrEquals($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualTrueEqual($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::create(2000, 1, 1, 0, 0, 0)->gte($class::create(2000, 1, 1, 0, 0, 0)));
        });
        $this->assertTrue($class::create(2000, 1, 1, 0, 0, 0)->greaterThanOrEquals($class::create(2000, 1, 1, 0, 0, 0)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::createFromDate(2000, 1, 1)->gte($class::createFromDate(2000, 1, 2)));
        });
        $this->assertFalse($class::createFromDate(2000, 1, 1)->greaterThanOrEquals($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::createFromDate(2000, 1, 1)->lt($class::createFromDate(2000, 1, 2)));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThan($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::createFromDate(2000, 1, 1)->lte($class::createFromDate(1999, 12, 31)));
        });
        $this->assertFalse($class::createFromDate(2000, 1, 1)->lessThanOrEquals($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualTrue($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::createFromDate(2000, 1, 1)->lte($class::createFromDate(2000, 1, 2)));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThanOrEquals($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualTrueEqual($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertTrue($class::createFromDate(2000, 1, 1)->lte($class::createFromDate(2000, 1, 1)));
        });
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThanOrEquals($class::createFromDate(2000, 1, 1)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualFalse($class)
    {
        $this->deprecated(function () use ($class) {
            $this->assertFalse($class::createFromDate(2000, 1, 1)->lte($class::createFromDate(1999, 12, 31)));
        });
        $this->assertFalse($class::createFromDate(2000, 1, 1)->lessThan($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenEqualTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 15)->between(
            $class::createFromDate(2000, 1, 1),
            $class::createFromDate(2000, 1, 31),
            true
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenNotEqualTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 15)->between(
            $class::createFromDate(2000, 1, 1),
            $class::createFromDate(2000, 1, 31),
            false
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenEqualFalse($class)
    {
        $this->assertFalse($class::createFromDate(1999, 12, 31)->between(
            $class::createFromDate(2000, 1, 1),
            $class::createFromDate(2000, 1, 31),
            true
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenNotEqualFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->between(
            $class::createFromDate(2000, 1, 1),
            $class::createFromDate(2000, 1, 31),
            false
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenEqualSwitchTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 15)->between(
            $class::createFromDate(2000, 1, 31),
            $class::createFromDate(2000, 1, 1),
            true
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenNotEqualSwitchTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 15)->between(
            $class::createFromDate(2000, 1, 31),
            $class::createFromDate(2000, 1, 1),
            false
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenEqualSwitchFalse($class)
    {
        $this->assertFalse($class::createFromDate(1999, 12, 31)->between(
            $class::createFromDate(2000, 1, 31),
            $class::createFromDate(2000, 1, 1),
            true
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testBetweenNotEqualSwitchFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->between(
            $class::createFromDate(2000, 1, 31),
            $class::createFromDate(2000, 1, 1),
            false
        ));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->min() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinWithNow($class)
    {
        $dt = $class::create(2012, 1, 1, 0, 0, 0)->min();
        $this->assertDateTime($dt, 2012, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinWithInstance($class)
    {
        $dt1 = $class::create(2013, 12, 31, 23, 59, 59);
        $dt2 = $class::create(2012, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertDateTime($dt2, 2012, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaxIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->max() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaxWithNow($class)
    {
        $dt = $class::create(2099, 12, 31, 23, 59, 59)->max();
        $this->assertDateTime($dt, 2099, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaxWithInstance($class)
    {
        $dt1 = $class::create(2012, 1, 1, 0, 0, 0);
        $dt2 = $class::create(2099, 12, 31, 23, 59, 59)->max($dt1);
        $this->assertDateTime($dt2, 2099, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsBirthday($class)
    {
        $dt = $class::now();
        $aBirthday = $dt->subYears(1);
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDays(1);
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = $class::createFromDate(1987, 4, 23);
        $dt2 = $class::createFromDate(2014, 9, 26);
        $dt3 = $class::createFromDate(2014, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testClosest($class)
    {
        $instance = $class::create(2015, 5, 28, 12, 0, 0);
        $dt1 = $class::create(2015, 5, 28, 11, 0, 0);
        $dt2 = $class::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testClosestWithEquals($class)
    {
        $instance = $class::create(2015, 5, 28, 12, 0, 0);
        $dt1 = $class::create(2015, 5, 28, 12, 0, 0);
        $dt2 = $class::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFarthest($class)
    {
        $instance = $class::create(2015, 5, 28, 12, 0, 0);
        $dt1 = $class::create(2015, 5, 28, 11, 0, 0);
        $dt2 = $class::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testFarthestWithEquals($class)
    {
        $instance = $class::create(2015, 5, 28, 12, 0, 0);
        $dt1 = $class::create(2015, 5, 28, 12, 0, 0);
        $dt2 = $class::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }
}
