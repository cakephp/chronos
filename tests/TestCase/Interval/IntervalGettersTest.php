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

namespace Cake\Chronos\Test\TestCase\Interval;

use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Test\TestCase\TestCase;

class IntervalGettersTest extends TestCase
{
    public function testGettersThrowExceptionOnUnknownGetter()
    {
        $this->deprecated(function () {
            $this->expectException(\InvalidArgumentException::class);

            ChronosInterval::year()->sdfsdfss;
        });
    }

    public function testYearsGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(4, $d->years);
        });
    }

    public function testMonthsGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(5, $d->months);
        });
    }

    public function testWeeksGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(6, $d->weeks);
        });
    }

    public function testDayzExcludingWeeksGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(5, $d->daysExcludeWeeks);
            $this->assertSame(5, $d->dayzExcludeWeeks);
        });
    }

    public function testDayzGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(6 * 7 + 5, $d->dayz);
        });
    }

    public function testHoursGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(8, $d->hours);
        });
    }

    public function testMinutesGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $this->assertSame(9, $d->minutes);
        });
    }

    public function testSecondsGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10, 123);
            $this->assertSame(10, $d->seconds);
        });
    }

    public function testMicrosecondsGetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10, 123);
            $this->assertSame(123, $d->microseconds);
        });
    }
}
