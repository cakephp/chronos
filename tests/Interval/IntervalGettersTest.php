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

namespace Cake\Chronos\Test\Interval;

use Cake\Chronos\ChronosInterval;
use TestCase;

class IntervalGettersTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGettersThrowExceptionOnUnknownGetter()
    {
        ChronosInterval::year()->sdfsdfss;
    }

    public function testYearsGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(4, $d->years);
    }

    public function testMonthsGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(5, $d->months);
    }

    public function testWeeksGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(6, $d->weeks);
    }

    public function testDayzExcludingWeeksGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(5, $d->daysExcludeWeeks);
        $this->assertSame(5, $d->dayzExcludeWeeks);
    }

    public function testDayzGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(6 * 7 + 5, $d->dayz);
    }

    public function testHoursGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(8, $d->hours);
    }

    public function testMinutesGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(9, $d->minutes);
    }

    public function testSecondsGetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(10, $d->seconds);
    }
}
