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
