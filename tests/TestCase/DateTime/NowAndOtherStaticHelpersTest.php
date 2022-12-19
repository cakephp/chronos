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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;
use DateTimeZone;

class NowAndOtherStaticHelpersTest extends TestCase
{
    public function testNow()
    {
        $dt = Chronos::now();
        $this->assertSame(time(), $dt->timestamp);
    }

    public function testNowWithTimezone()
    {
        $dt = Chronos::now('Europe/London');
        $this->assertSame(time(), $dt->timestamp);
        $this->assertSame('Europe/London', $dt->tzName);
    }

    public function testToday()
    {
        $dt = Chronos::today();
        $this->assertSame(date('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTodayWithTimezone()
    {
        $dt = Chronos::today('Europe/London');
        $dt2 = new DateTime('now', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrow()
    {
        $dt = Chronos::tomorrow();
        $dt2 = new DateTime('tomorrow');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrowWithTimezone()
    {
        $dt = Chronos::tomorrow('Europe/London');
        $dt2 = new DateTime('tomorrow', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterday()
    {
        $dt = Chronos::yesterday();
        $dt2 = new DateTime('yesterday');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterdayWithTimezone()
    {
        $dt = Chronos::yesterday('Europe/London');
        $dt2 = new DateTime('yesterday', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testMinValue()
    {
        $this->assertLessThanOrEqual(-2147483647, Chronos::minValue()->getTimestamp());
    }

    public function testMinValueNonUtcTimezone()
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertLessThanOrEqual(-2147483647, Chronos::minValue()->getTimestamp());
        $this->assertTrue(Chronos::now()->greaterThan(Chronos::minValue()));
    }

    public function testMaxValue()
    {
        $this->assertGreaterThanOrEqual(2147483647, Chronos::maxValue()->getTimestamp());
    }

    public function testMaxValueNonUtcTimezone()
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertGreaterThanOrEqual(2147483647, Chronos::maxValue()->getTimestamp());
        $this->assertTrue(Chronos::now()->lessThan(Chronos::maxValue()));
    }
}
