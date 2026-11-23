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
    public function testNow(): void
    {
        $dt = Chronos::now();
        $this->assertSame(time(), $dt->timestamp);
    }

    public function testNowWithTimezone(): void
    {
        $dt = Chronos::now('Europe/London');
        $this->assertSame(time(), $dt->timestamp);
        $this->assertSame('Europe/London', $dt->tzName);
    }

    public function testToday(): void
    {
        $dt = Chronos::today();
        $this->assertSame(date('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTodayWithTimezone(): void
    {
        $dt = Chronos::today('Europe/London');
        $dt2 = new DateTime('now', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrow(): void
    {
        $dt = Chronos::tomorrow();
        $dt2 = new DateTime('tomorrow');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrowWithTimezone(): void
    {
        $dt = Chronos::tomorrow('Europe/London');
        $dt2 = new DateTime('tomorrow', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterday(): void
    {
        $dt = Chronos::yesterday();
        $dt2 = new DateTime('yesterday');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterdayWithTimezone(): void
    {
        $dt = Chronos::yesterday('Europe/London');
        $dt2 = new DateTime('yesterday', new DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testMinValue(): void
    {
        $this->assertLessThanOrEqual(-2147483647, Chronos::minValue()->getTimestamp());
    }

    public function testMinValueNonUtcTimezone(): void
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertLessThanOrEqual(-2147483647, Chronos::minValue()->getTimestamp());
        $this->assertTrue(Chronos::now()->greaterThan(Chronos::minValue()));
    }

    public function testMaxValue(): void
    {
        $this->assertGreaterThanOrEqual(2147483647, Chronos::maxValue()->getTimestamp());
    }

    public function testMaxValueNonUtcTimezone(): void
    {
        date_default_timezone_set('Europe/Amsterdam');

        $this->assertGreaterThanOrEqual(2147483647, Chronos::maxValue()->getTimestamp());
        $this->assertTrue(Chronos::now()->lessThan(Chronos::maxValue()));
    }
}
