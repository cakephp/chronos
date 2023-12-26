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

use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

class ComparisonTest extends TestCase
{
    public function testEqualToTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->equals(ChronosDate::create(2000, 1, 1)));
    }

    public function testEqualToFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->equals(ChronosDate::create(2000, 1, 2)));
    }

    public function testNotEqualToTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->notEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testNotEqualToFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->notEquals(ChronosDate::create(2000, 1, 1)));
    }

    public function testGreaterThanTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->greaterThan(ChronosDate::create(1999, 12, 31)));
    }

    public function testGreaterThanFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->greaterThan(ChronosDate::create(2000, 1, 2)));
    }

    public function testGreaterThanOrEqualTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->greaterThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testGreaterThanOrEqualTrueEqual()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1, 0, 0, 0)->greaterThanOrEquals(ChronosDate::create(2000, 1, 1, 0, 0, 0)));
    }

    public function testGreaterThanOrEqualFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->greaterThanOrEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThan(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testLessThanOrEqualTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanOrEqualTrueEqual()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(2000, 1, 1)));
    }

    public function testLessThanOrEqualFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testBetweenEqualTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            true
        ));
    }

    public function testBetweenNotEqualTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            false
        ));
    }

    public function testBetweenEqualFalse()
    {
        $this->assertFalse(ChronosDate::create(1999, 12, 31)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            true
        ));
    }

    public function testBetweenNotEqualFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            false
        ));
    }

    public function testBetweenEqualSwitchTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            true
        ));
    }

    public function testBetweenNotEqualSwitchTrue()
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            false
        ));
    }

    public function testBetweenEqualSwitchFalse()
    {
        $this->assertFalse(ChronosDate::create(1999, 12, 31)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            true
        ));
    }

    public function testBetweenNotEqualSwitchFalse()
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            false
        ));
    }

    public function testClosest()
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testClosestWithEquals()
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 10);
        $dt2 = ChronosDate::create(2015, 5, 11);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testClosestWithOthers(): void
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $dt3 = ChronosDate::create(2015, 5, 21);
        $dt4 = ChronosDate::create(2015, 5, 22);
        $closest = $instance->closest($dt4, $dt3, $dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testFarthest()
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }

    public function testFarthestWithEquals()
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 10);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }

    public function testFarthestWithOthers(): void
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $dt3 = ChronosDate::create(2015, 5, 21);
        $dt4 = ChronosDate::create(2015, 5, 22);
        $dt5 = ChronosDate::create(2015, 5, 23);
        $Farthest = $instance->farthest($dt1, $dt2, $dt3, $dt4, $dt5);
        $this->assertSame($dt5, $Farthest);
    }
}
