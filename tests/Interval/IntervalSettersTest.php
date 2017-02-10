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

class IntervalSettersTest extends TestCase
{

    public function testYearsSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->years = 2;
        $this->assertSame(2, $d->years);
    }

    public function testMonthsSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->months = 11;
        $this->assertSame(11, $d->months);
    }

    public function testWeeksSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->weeks = 11;
        $this->assertSame(11, $d->weeks);
        $this->assertSame(7 * 11, $d->dayz);
    }

    public function testDayzSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->dayz = 11;
        $this->assertSame(11, $d->dayz);
        $this->assertSame(1, $d->weeks);
        $this->assertSame(4, $d->dayzExcludeWeeks);
    }

    public function testHoursSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->hours = 12;
        $this->assertSame(12, $d->hours);
    }

    public function testMinutesSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->minutes = 11;
        $this->assertSame(11, $d->minutes);
    }

    public function testSecondsSetter()
    {
        $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
        $d->seconds = 34;
        $this->assertSame(34, $d->seconds);
    }

    public function testFluentSetters()
    {
        $ci = ChronosInterval::years(4)->months(2)->dayz(5)->hours(3)->minute()->seconds(59);
        $this->assertInstanceOf(ChronosInterval::class, $ci);
        $this->assertDateTimeInterval($ci, 4, 2, 5, 3, 1, 59);

        $ci = ChronosInterval::years(4)->months(2)->weeks(2)->hours(3)->minute()->seconds(59);
        $this->assertInstanceOf(ChronosInterval::class, $ci);
        $this->assertDateTimeInterval($ci, 4, 2, 14, 3, 1, 59);
    }

    public function testFluentSettersDaysOverwritesWeeks()
    {
        $ci = ChronosInterval::weeks(3)->days(5);
        $this->assertDateTimeInterval($ci, 0, 0, 5, 0, 0, 0);
    }

    public function testFluentSettersWeeksOverwritesDays()
    {
        $ci = ChronosInterval::days(5)->weeks(3);
        $this->assertDateTimeInterval($ci, 0, 0, 3 * 7, 0, 0, 0);
    }

    public function testFluentSettersWeeksAndDaysIsCumulative()
    {
        $ci = ChronosInterval::year(5)->weeksAndDays(2, 6);
        $this->assertDateTimeInterval($ci, 5, 0, 20, 0, 0, 0);
        $this->assertSame(20, $ci->dayz);
        $this->assertSame(2, $ci->weeks);
        $this->assertSame(6, $ci->dayzExcludeWeeks);
    }
}
