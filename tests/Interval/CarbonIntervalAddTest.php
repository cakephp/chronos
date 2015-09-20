<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\Interval;

use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Chronos;
use DateInterval;
use TestCase;

class CarbonIntervalAddTest extends TestCase
{

    public function testAdd()
    {
        $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add(new DateInterval('P2Y1M5DT22H33M44S'));
        $this->assertDateTimeInterval($ci, 6, 4, 54, 30, 43, 55);
    }

    public function testAddWithDiffDateInterval()
    {
        $diff = Chronos::now()->diff(Chronos::now()->addWeeks(3));
        $ci   = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertDateTimeInterval($ci, 4, 3, 70, 8, 10, 11);
    }

    public function testAddWithNegativeDiffDateInterval()
    {
        $diff = Chronos::now()->diff(Chronos::now()->subWeeks(3));
        $ci   = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertDateTimeInterval($ci, 4, 3, 28, 8, 10, 11);
    }
}
