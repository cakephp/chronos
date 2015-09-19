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
use TestCase;

class RelativeTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondsSinceMidnight($class)
    {
        $d = $class::today()->addSeconds(30);
        $this->assertSame(30, $d->secondsSinceMidnight());

        $d = $class::today()->addDays(1);
        $this->assertSame(0, $d->secondsSinceMidnight());

        $d = $class::today()->addDays(1)->addSeconds(120);
        $this->assertSame(120, $d->secondsSinceMidnight());

        $d = $class::today()->addMonths(3)->addSeconds(42);
        $this->assertSame(42, $d->secondsSinceMidnight());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondsUntilEndOfDay($class)
    {
        $d = $class::today()->endOfDay();
        $this->assertSame(0, $d->secondsUntilEndOfDay());

        $d = $class::today()->endOfDay()->subSeconds(60);
        $this->assertSame(60, $d->secondsUntilEndOfDay());

        $d = $class::create(2014, 10, 24, 12, 34, 56);
        $this->assertSame(41103, $d->secondsUntilEndOfDay());

        $d = $class::create(2014, 10, 24, 0, 0, 0);
        $this->assertSame(86399, $d->secondsUntilEndOfDay());
    }
}
