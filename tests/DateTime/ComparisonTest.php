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

class ComparisonTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualToTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->equalTo($class::createFromDate(2000, 1, 1)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualToFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->equalTo($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualWithTimezoneTrue($class)
    {
        $this->assertTrue($class::create(2000, 1, 1, 12, 0, 0, 'America/Toronto')->equalTo($class::create(
            2000,
            1,
            1,
            9,
            0,
            0,
            'America/Vancouver'
        )));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualWithTimezoneFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1, 'America/Toronto')->equalTo($class::createFromDate(
            2000,
            1,
            1,
            'America/Vancouver'
        )));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualToTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->notEqualTo($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualToFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->notEqualTo($class::createFromDate(2000, 1, 1)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNotEqualWithTimezone($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1, 'America/Toronto')->notEqualTo($class::createFromDate(
            2000,
            1,
            1,
            'America/Vancouver'
        )));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->greaterThan($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->greaterThan($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanWithTimezoneTrue($class)
    {
        $dt1 = $class::create(2000, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = $class::create(2000, 1, 1, 8, 59, 59, 'America/Vancouver');
        $this->assertTrue($dt1->greaterThan($dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanWithTimezoneFalse($class)
    {
        $dt1 = $class::create(2000, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = $class::create(2000, 1, 1, 9, 0, 1, 'America/Vancouver');
        $this->assertFalse($dt1->greaterThan($dt2));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualToTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->greaterThanOrEqualTo($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualToTrueEqual($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->greaterThanOrEqualTo($class::createFromDate(2000, 1, 1)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testGreaterThanOrEqualToFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->greaterThanOrEqualTo($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThan($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->lessThan($class::createFromDate(1999, 12, 31)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualToTrue($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThanOrEqualTo($class::createFromDate(2000, 1, 2)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualToTrueEqual($class)
    {
        $this->assertTrue($class::createFromDate(2000, 1, 1)->lessThanOrEqualTo($class::createFromDate(2000, 1, 1)));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testLessThanOrEqualToFalse($class)
    {
        $this->assertFalse($class::createFromDate(2000, 1, 1)->lessThanOrEqualTo($class::createFromDate(1999, 12, 31)));
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
    public function testMinimumIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->minimum() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinimumWithNow($class)
    {
        $dt = $class::create(2012, 1, 1, 0, 0, 0)->minimum();
        $this->assertDateTime($dt, 2012, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinimumWithInstance($class)
    {
        $dt1 = $class::create(2013, 12, 31, 23, 59, 59);
        $dt2 = $class::create(2012, 1, 1, 0, 0, 0)->minimum($dt1);
        $this->assertDateTime($dt2, 2012, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaximumIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->maximum() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaximumWithNow($class)
    {
        $dt = $class::create(2099, 12, 31, 23, 59, 59)->maximum();
        $this->assertDateTime($dt, 2099, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaximumWithInstance($class)
    {
        $dt1 = $class::create(2012, 1, 1, 0, 0, 0);
        $dt2 = $class::create(2099, 12, 31, 23, 59, 59)->maximum($dt1);
        $this->assertDateTime($dt2, 2099, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIsBirthday($class)
    {
        $dt = $class::now();
        $aBirthday = $dt->subYear();
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDay();
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = $class::createFromDate(1987, 4, 23);
        $dt2 = $class::createFromDate(2014, 9, 26);
        $dt3 = $class::createFromDate(2014, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));
    }
}
