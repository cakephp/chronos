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
    public function testEqualToTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->equals(ChronosDate::create(2000, 1, 1)));
    }

    public function testEqualToFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->equals(ChronosDate::create(2000, 1, 2)));
    }

    public function testNotEqualToTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->notEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testNotEqualToFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->notEquals(ChronosDate::create(2000, 1, 1)));
    }

    public function testGreaterThanTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->greaterThan(ChronosDate::create(1999, 12, 31)));
    }

    public function testGreaterThanFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->greaterThan(ChronosDate::create(2000, 1, 2)));
    }

    public function testGreaterThanOrEqualTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->greaterThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testGreaterThanOrEqualTrueEqual(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->greaterThanOrEquals(ChronosDate::create(2000, 1, 1)));
    }

    public function testGreaterThanOrEqualFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->greaterThanOrEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThan(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testLessThanOrEqualTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(2000, 1, 2)));
    }

    public function testLessThanOrEqualTrueEqual(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(2000, 1, 1)));
    }

    public function testLessThanOrEqualFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->lessThanOrEquals(ChronosDate::create(1999, 12, 31)));
    }

    public function testBetweenEqualTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            true,
        ));
    }

    public function testBetweenNotEqualTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            false,
        ));
    }

    public function testBetweenEqualFalse(): void
    {
        $this->assertFalse(ChronosDate::create(1999, 12, 31)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            true,
        ));
    }

    public function testBetweenNotEqualFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->between(
            ChronosDate::create(2000, 1, 1),
            ChronosDate::create(2000, 1, 31),
            false,
        ));
    }

    public function testBetweenEqualSwitchTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            true,
        ));
    }

    public function testBetweenNotEqualSwitchTrue(): void
    {
        $this->assertTrue(ChronosDate::create(2000, 1, 15)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            false,
        ));
    }

    public function testBetweenEqualSwitchFalse(): void
    {
        $this->assertFalse(ChronosDate::create(1999, 12, 31)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            true,
        ));
    }

    public function testBetweenNotEqualSwitchFalse(): void
    {
        $this->assertFalse(ChronosDate::create(2000, 1, 1)->between(
            ChronosDate::create(2000, 1, 31),
            ChronosDate::create(2000, 1, 1),
            false,
        ));
    }

    public function testClosest(): void
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testClosestWithEquals(): void
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

    public function testFarthest(): void
    {
        $instance = ChronosDate::create(2015, 5, 10);
        $dt1 = ChronosDate::create(2015, 5, 4);
        $dt2 = ChronosDate::create(2015, 5, 20);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);
    }

    public function testFarthestWithEquals(): void
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
