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

class NowAndOtherStaticHelpersTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNow($class)
    {
        $dt = $class::now();
        $this->assertSame(time(), $dt->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testNowWithTimezone($class)
    {
        $dt = $class::now('Europe/London');
        $this->assertSame(time(), $dt->timestamp);
        $this->assertSame('Europe/London', $dt->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToday($class)
    {
        $dt = $class::today();
        $this->assertSame(date('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTodayWithTimezone($class)
    {
        $dt = $class::today('Europe/London');
        $dt2 = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTomorrow($class)
    {
        $dt = $class::tomorrow();
        $dt2 = new \DateTime('tomorrow');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTomorrowWithTimezone($class)
    {
        $dt = $class::tomorrow('Europe/London');
        $dt2 = new \DateTime('tomorrow', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testYesterday($class)
    {
        $dt = $class::yesterday();
        $dt2 = new \DateTime('yesterday');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testYesterdayWithTimezone($class)
    {
        $dt = $class::yesterday('Europe/London');
        $dt2 = new \DateTime('yesterday', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinValue($class)
    {
        $this->assertLessThanOrEqual(-2147483647, $class::minValue()->getTimestamp());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMinValueNonUtcTimezone($class)
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertLessThanOrEqual(-2147483647, $class::minValue()->getTimestamp());
        $this->assertTrue($class::now()->gt($class::minValue()));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaxValue($class)
    {
        $this->assertGreaterThanOrEqual(2147483647, $class::maxValue()->getTimestamp());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testMaxValueNonUtcTimezone($class)
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertGreaterThanOrEqual(2147483647, $class::maxValue()->getTimestamp());
        $this->assertTrue($class::now()->lt($class::maxValue()));
    }
}
