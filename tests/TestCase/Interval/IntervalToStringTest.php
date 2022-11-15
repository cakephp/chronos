<?php
declare(strict_types=1);

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

namespace Cake\Chronos\Test\TestCase\Interval;

use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Test\TestCase\TestCase;
use DateInterval;

class IntervalToStringTest extends TestCase
{
    public function testZeroInterval()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(0, 0, 0, 0, 0, 0, 0);
            $this->assertSame('PT0S', (string)$ci);
        });
    }

    public function testNegativeArguments()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(1, -2, 0, 15);
            $this->assertSame('P1Y15D', (string)$ci, 'Negative arguments are not considered');
        });
    }

    /**
     * Date section
     */
    public function testYearInterval()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval();
            $ci1 = new ChronosInterval(1);
            $ci2 = new ChronosInterval(0, 12, 0, 0);
            $ci3 = new ChronosInterval(0, 0, 0, 365);
            $ci4 = new ChronosInterval(0, 0, 52, 1);

            $this->assertSame('P1Y', (string)$ci);
            $this->assertSame('P1Y', (string)$ci1);
            $this->assertSame('P1Y', (string)$ci2);
            $this->assertSame('P1Y', (string)$ci3);
            $this->assertSame('P1Y', (string)$ci4);
        });
    }

    public function testMonthInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 1);
            $ci2 = new ChronosInterval(0, 0, 0, 30, 10);
            $ci3 = new ChronosInterval(0, 0, 4, 2, 10);

            $this->assertSame('P1M', (string)$ci1);
            $this->assertSame('P1M', (string)$ci2);
            $this->assertSame('P1M', (string)$ci3);
        });
    }

    public function testWeekInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 0, 1);
            $ci2 = new ChronosInterval(0, 0, 0, 7);
            $ci3 = new ChronosInterval(0, 0, 0, 0, 7 * 24);
            $ci4 = new ChronosInterval(0, 0, 0, 0, 0, 7 * 24 * 60);

            $this->assertSame('P7D', (string)$ci1);
            $this->assertSame('P7D', (string)$ci2);
            $this->assertSame('P7D', (string)$ci3);
            $this->assertSame('P7D', (string)$ci4);
        });
    }

    public function testDayInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 0, 0, 1);
            $ci2 = new ChronosInterval(0, 0, 0, 0, 24);
            $ci3 = new ChronosInterval(0, 0, 0, 0, 0, 24 * 60);
            $ci4 = new ChronosInterval(0, 0, 0, 0, 0, 0, 24 * 60 * 60);

            $this->assertSame('P1D', (string)$ci1);
            $this->assertSame('P1D', (string)$ci2);
            $this->assertSame('P1D', (string)$ci3);
            $this->assertSame('P1D', (string)$ci4);
        });
    }

    public function testMixedDateInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(1, 2, 0, 3);
            $ci2 = new ChronosInterval(0, 14, 0, 3);
            $this->assertSame('P1Y2M3D', (string)$ci1);
            $this->assertSame('P1Y2M3D', (string)$ci2);
        });
    }

    /**
     * Time section
     */
    public function testHourInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 0, 0, 0, 1);
            $ci2 = new ChronosInterval(0, 0, 0, 0, 0, 60);
            $ci3 = new ChronosInterval(0, 0, 0, 0, 0, 0, 3600);

            $this->assertSame('PT1H', (string)$ci1);
            $this->assertSame('PT1H', (string)$ci2);
            $this->assertSame('PT1H', (string)$ci3);
        });
    }

    public function testMinuteInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 0, 0, 0, 0, 1);
            $ci2 = new ChronosInterval(0, 0, 0, 0, 0, 0, 60);

            $this->assertSame('PT1M', (string)$ci1);
            $this->assertSame('PT1M', (string)$ci2);
        });
    }

    public function testSecondInterval()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(0, 0, 0, 0, 0, 0, 1);

            $this->assertSame('PT1S', (string)$ci);
        });
    }

    public function testMixedTimeInterval()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(0, 0, 0, 0, 1, 2, 3);
            $this->assertSame('PT1H2M3S', (string)$ci);
        });
    }

    /**
     * Date and Time sections
     */
    public function testMixedDateAndTimeInterval()
    {
        $this->deprecated(function () {
            $ci1 = new ChronosInterval(0, 0, 0, 0, 48, 120);
            $ci2 = new ChronosInterval(0, 24, 0, 0, 48, 120);

            $this->assertSame('P2DT2H', (string)$ci1);
            $this->assertSame('P2Y2DT2H', (string)$ci2);
        });
    }

    public function testCreatingInstanceEquals()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(1, 2, 0, 3, 4, 5, 6);
            $this->assertSame(
                (string)$ci,
                (string)ChronosInterval::instance(new DateInterval((string)$ci))
            );
        });
    }

    public function testNegativeInterval()
    {
        $this->deprecated(function () {
            $ci = new ChronosInterval(1, 2, 0, 3, 4, 5, 6);
            $ci->invert = 1;
            $this->assertSame('-P1Y2M3DT4H5M6S', (string)$ci);
        });
    }
}
