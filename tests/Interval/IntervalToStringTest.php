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
use DateInterval;
use TestCase;

class IntervalToStringTest extends TestCase
{
    public function testZeroInterval()
    {
        $ci = new ChronosInterval(0, 0, 0, 0, 0, 0, 0);
        $this->assertEquals('PT0S', (string)$ci);
    }

    public function testNegativeArguments()
    {
        $ci = new ChronosInterval(1, -2, 0, 15);
        $this->assertEquals('P1Y15D', (string)$ci, 'Negative arguments are not considered');
    }

    /**
     * Date section
     */
    public function testYearInterval()
    {
        $ci = new ChronosInterval();
        $ci1 = new ChronosInterval(1);
        $ci2 = new ChronosInterval(0, 12, 0, 0);
        $ci3 = new ChronosInterval(0, 0, 0, 365);
        $ci4 = new ChronosInterval(0, 0, 52, 1);

        $this->assertEquals('P1Y', (string)$ci);
        $this->assertEquals('P1Y', (string)$ci1);
        $this->assertEquals('P1Y', (string)$ci2);
        $this->assertEquals('P1Y', (string)$ci3);
        $this->assertEquals('P1Y', (string)$ci4);
    }

    public function testMonthInterval()
    {
        $ci1 = new ChronosInterval(0, 1);
        $ci2 = new ChronosInterval(0, 0, 0, 30, 10);
        $ci3 = new ChronosInterval(0, 0, 4, 2, 10);

        $this->assertEquals('P1M', (string)$ci1);
        $this->assertEquals('P1M', (string)$ci2);
        $this->assertEquals('P1M', (string)$ci3);
    }

    public function testWeekInterval()
    {
        $ci1 = new ChronosInterval(0, 0, 1);
        $ci2 = new ChronosInterval(0, 0, 0, 7);
        $ci3 = new ChronosInterval(0, 0, 0, 0, 7 * 24);
        $ci4 = new ChronosInterval(0, 0, 0, 0, 0, 7 * 24 * 60);

        $this->assertEquals('P7D', (string)$ci1);
        $this->assertEquals('P7D', (string)$ci2);
        $this->assertEquals('P7D', (string)$ci3);
        $this->assertEquals('P7D', (string)$ci4);
    }

    public function testDayInterval()
    {
        $ci1 = new ChronosInterval(0, 0, 0, 1);
        $ci2 = new ChronosInterval(0, 0, 0, 0, 24);
        $ci3 = new ChronosInterval(0, 0, 0, 0, 0, 24 * 60);
        $ci4 = new ChronosInterval(0, 0, 0, 0, 0, 0, 24 * 60 * 60);

        $this->assertEquals('P1D', (string)$ci1);
        $this->assertEquals('P1D', (string)$ci2);
        $this->assertEquals('P1D', (string)$ci3);
        $this->assertEquals('P1D', (string)$ci4);
    }

    public function testMixedDateInterval()
    {
        $ci1 = new ChronosInterval(1, 2, 0, 3);
        $ci2 = new ChronosInterval(0, 14, 0, 3);
        $this->assertEquals('P1Y2M3D', (string)$ci1);
        $this->assertEquals('P1Y2M3D', (string)$ci2);
    }

    /**
     * Time section
     */
    public function testHourInterval()
    {
        $ci1 = new ChronosInterval(0, 0, 0, 0, 1);
        $ci2 = new ChronosInterval(0, 0, 0, 0, 0, 60);
        $ci3 = new ChronosInterval(0, 0, 0, 0, 0, 0, 3600);

        $this->assertEquals('PT1H', (string)$ci1);
        $this->assertEquals('PT1H', (string)$ci2);
        $this->assertEquals('PT1H', (string)$ci3);
    }

    public function testMinuteInterval()
    {
        $ci1 = new ChronosInterval(0, 0, 0, 0, 0, 1);
        $ci2 = new ChronosInterval(0, 0, 0, 0, 0, 0, 60);

        $this->assertEquals('PT1M', (string)$ci1);
        $this->assertEquals('PT1M', (string)$ci2);
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

    /**
     * Date and Time sections
     */
    public function testMixedDateAndTimeInterval()
    {
        $ci1 = new ChronosInterval(0, 0, 0, 0, 48, 120);
        $ci2 = new ChronosInterval(0, 24, 0, 0, 48, 120);

        $this->assertEquals('P2DT2H', (string)$ci1);
        $this->assertEquals('P2Y2DT2H', (string)$ci2);
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
        $this->assertEquals('-P1Y2M3DT4H5M6S', (string)$ci);
    }
}
