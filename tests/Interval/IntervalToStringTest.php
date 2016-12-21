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

namespace Interval;

use Cake\Chronos\ChronosInterval;
use DateInterval;
use TestCase;

class IntervalToStringTest extends TestCase
{
    public function testZeroInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 0, 0, 0);
        $this->assertEquals('PT0S', (string)$ci);
    }

    public function testYearInterval()
    {
        $ci = new ChronosInterval();
        $this->assertEquals('P1Y', (string)$ci);
    }

    public function testMonthInterval()
    {
        $ci = new ChronosInterval(0, 1);
        $this->assertEquals('P1M', (string)$ci);
    }

    public function testWeekInterval()
    {
        $ci = new ChronosInterval(0, 0, 1);
        $this->assertEquals('P7D', (string)$ci);
    }

    public function testDayInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 1);
        $this->assertEquals('P1D', (string)$ci);
    }

    public function testMixedDateInterval()
    {
        $ci = new ChronosInterval(1, 2, 0, 3);
        $this->assertEquals('P1Y2M3D', (string)$ci);
    }

    public function testHourInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 1);
        $this->assertEquals('PT1H', (string)$ci);
    }

    public function testMinuteInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 0, 1);
        $this->assertEquals('PT1M', (string)$ci);
    }

    public function testSecondInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 0, 0, 1);
        $this->assertEquals('PT1S', (string)$ci);
    }

    public function testMixedTimeInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 1, 2, 3);
        $this->assertEquals('PT1H2M3S', (string)$ci);
    }

    public function testMixedDateAndTimeInterval()
    {
        $ci = new ChronosInterval(1, 2, 0, 3, 4, 5, 6);
        $this->assertEquals('P1Y2M3DT4H5M6S', (string)$ci);
    }

    public function testCreatingInstanceEquals()
    {
        $ci = new ChronosInterval(1, 2, 0, 3, 4, 5, 6);
        $this->assertEquals(
            $ci,
            ChronosInterval::instance(new DateInterval((string)$ci))
        );
    }

    public function testNegativeInterval()
    {
        $ci = new ChronosInterval(1, 2, 0, 3, 4, 5, 6);
        $ci->invert = 1;
        $this->assertEquals('-P1Y2M3DT4H5M6S', (string) $ci);
    }
}
