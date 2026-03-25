<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterval;
use DateInterval;

class ChronosIntervalTest extends TestCase
{
    public function testCreateFromSpec(): void
    {
        $interval = ChronosInterval::create('P1Y2M3D');
        $this->assertSame(1, $interval->y);
        $this->assertSame(2, $interval->m);
        $this->assertSame(3, $interval->d);
    }

    public function testCreateFromValues(): void
    {
        $interval = ChronosInterval::createFromValues(1, 2, 0, 3, 4, 5, 6);
        $this->assertSame(1, $interval->y);
        $this->assertSame(2, $interval->m);
        $this->assertSame(3, $interval->d);
        $this->assertSame(4, $interval->h);
        $this->assertSame(5, $interval->i);
        $this->assertSame(6, $interval->s);
    }

    public function testCreateFromValuesWithWeeks(): void
    {
        $interval = ChronosInterval::createFromValues(weeks: 2);
        $this->assertSame(14, $interval->d);
    }

    public function testInstance(): void
    {
        $native = new DateInterval('P1D');
        $interval = ChronosInterval::instance($native);
        $this->assertSame(1, $interval->d);
    }

    public function testToNative(): void
    {
        $interval = ChronosInterval::create('P1Y');
        $native = $interval->toNative();
        $this->assertInstanceOf(DateInterval::class, $native);
        $this->assertSame(1, $native->y);
    }

    public function testToIso8601String(): void
    {
        $interval = ChronosInterval::create('P1Y2M3DT4H5M6S');
        $this->assertSame('P1Y2M3DT4H5M6S', $interval->toIso8601String());
    }

    public function testToIso8601StringDateOnly(): void
    {
        $interval = ChronosInterval::create('P1Y2M3D');
        $this->assertSame('P1Y2M3D', $interval->toIso8601String());
    }

    public function testToIso8601StringTimeOnly(): void
    {
        $interval = ChronosInterval::create('PT4H5M6S');
        $this->assertSame('PT4H5M6S', $interval->toIso8601String());
    }

    public function testToIso8601StringEmpty(): void
    {
        $interval = ChronosInterval::create('P0D');
        $this->assertSame('PT0S', $interval->toIso8601String());
    }

    public function testToIso8601StringNegative(): void
    {
        $past = new Chronos('2020-01-01');
        $future = new Chronos('2021-02-02');
        $diff = $past->diff($future);
        $diff->invert = 1;

        $interval = ChronosInterval::instance($diff);
        $this->assertStringStartsWith('-P', $interval->toIso8601String());
    }

    public function testToString(): void
    {
        $interval = ChronosInterval::create('P1Y2M');
        $this->assertSame('P1Y2M', (string)$interval);
    }

    public function testFormat(): void
    {
        $interval = ChronosInterval::create('P1Y2M3D');
        $this->assertSame('1 years, 2 months, 3 days', $interval->format('%y years, %m months, %d days'));
    }

    public function testTotalSeconds(): void
    {
        $interval = ChronosInterval::create('PT1H30M');
        $this->assertSame(5400, $interval->totalSeconds());
    }

    public function testTotalDays(): void
    {
        $interval = ChronosInterval::create('P10D');
        $this->assertSame(10, $interval->totalDays());
    }

    public function testTotalDaysFromDiff(): void
    {
        $start = new Chronos('2020-01-01');
        $end = new Chronos('2020-01-11');
        $diff = $start->diff($end);

        $interval = ChronosInterval::instance($diff);
        $this->assertSame(10, $interval->totalDays());
    }

    public function testIsNegative(): void
    {
        $interval = ChronosInterval::create('P1D');
        $this->assertFalse($interval->isNegative());

        $past = new Chronos('2020-01-01');
        $future = new Chronos('2020-01-02');
        $diff = $future->diff($past);

        $interval = ChronosInterval::instance($diff);
        $this->assertTrue($interval->isNegative());
    }

    public function testIsZero(): void
    {
        $interval = ChronosInterval::create('P0D');
        $this->assertTrue($interval->isZero());

        $interval = ChronosInterval::create('P1D');
        $this->assertFalse($interval->isZero());
    }

    public function testPropertyAccess(): void
    {
        $interval = ChronosInterval::create('P1Y2M3DT4H5M6S');
        $this->assertSame(1, $interval->y);
        $this->assertSame(2, $interval->m);
        $this->assertSame(3, $interval->d);
        $this->assertSame(4, $interval->h);
        $this->assertSame(5, $interval->i);
        $this->assertSame(6, $interval->s);
    }

    public function testIsset(): void
    {
        $interval = ChronosInterval::create('P1Y');
        $this->assertTrue(isset($interval->y));
        $this->assertTrue(isset($interval->m));
    }

    public function testDebugInfo(): void
    {
        $interval = ChronosInterval::create('P1Y2M3D');
        $debug = $interval->__debugInfo();

        $this->assertArrayHasKey('interval', $debug);
        $this->assertArrayHasKey('years', $debug);
        $this->assertArrayHasKey('months', $debug);
        $this->assertArrayHasKey('days', $debug);
    }

    public function testWithMicroseconds(): void
    {
        $interval = ChronosInterval::createFromValues(seconds: 1, microseconds: 500000);
        $this->assertSame(0.5, $interval->f);
        $this->assertSame('PT1.5S', $interval->toIso8601String());
    }

    public function testWithMicrosecondsPartial(): void
    {
        $interval = ChronosInterval::createFromValues(seconds: 1, microseconds: 123456);
        $this->assertSame('PT1.123456S', $interval->toIso8601String());
    }

    public function testCreateFromDateString(): void
    {
        $interval = ChronosInterval::createFromDateString('1 year + 2 months');
        $this->assertSame(1, $interval->y);
        $this->assertSame(2, $interval->m);
    }

    public function testCreateFromDateStringComplex(): void
    {
        $interval = ChronosInterval::createFromDateString('3 days 4 hours');
        $this->assertSame(3, $interval->d);
        $this->assertSame(4, $interval->h);
    }

    public function testAdd(): void
    {
        $interval1 = ChronosInterval::create('P1Y2M');
        $interval2 = ChronosInterval::create('P2Y3M');

        $result = $interval1->add($interval2);

        $this->assertSame(3, $result->y);
        $this->assertSame(5, $result->m);
        // Original should be unchanged
        $this->assertSame(1, $interval1->y);
    }

    public function testAddWithDateInterval(): void
    {
        $interval = ChronosInterval::create('P1D');
        $native = new DateInterval('P2D');

        $result = $interval->add($native);

        $this->assertSame(3, $result->d);
    }

    public function testAddAllComponents(): void
    {
        $interval1 = ChronosInterval::create('P1Y2M3DT4H5M6S');
        $interval2 = ChronosInterval::create('P1Y1M1DT1H1M1S');

        $result = $interval1->add($interval2);

        $this->assertSame(2, $result->y);
        $this->assertSame(3, $result->m);
        $this->assertSame(4, $result->d);
        $this->assertSame(5, $result->h);
        $this->assertSame(6, $result->i);
        $this->assertSame(7, $result->s);
    }

    public function testSub(): void
    {
        $interval1 = ChronosInterval::create('P3Y5M');
        $interval2 = ChronosInterval::create('P1Y2M');

        $result = $interval1->sub($interval2);

        $this->assertSame(2, $result->y);
        $this->assertSame(3, $result->m);
        // Original should be unchanged
        $this->assertSame(3, $interval1->y);
    }

    public function testSubWithDateInterval(): void
    {
        $interval = ChronosInterval::create('P5D');
        $native = new DateInterval('P2D');

        $result = $interval->sub($native);

        $this->assertSame(3, $result->d);
    }

    public function testToDateString(): void
    {
        $interval = ChronosInterval::create('P1Y2M3DT4H5M6S');
        $this->assertSame('1 year 2 months 3 days 4 hours 5 minutes 6 seconds', $interval->toDateString());
    }

    public function testToDateStringSingular(): void
    {
        $interval = ChronosInterval::create('P1Y1M1DT1H1M1S');
        $this->assertSame('1 year 1 month 1 day 1 hour 1 minute 1 second', $interval->toDateString());
    }

    public function testToDateStringPartial(): void
    {
        $interval = ChronosInterval::create('P2M');
        $this->assertSame('2 months', $interval->toDateString());
    }

    public function testToDateStringEmpty(): void
    {
        $interval = ChronosInterval::create('P0D');
        $this->assertSame('0 seconds', $interval->toDateString());
    }

    public function testToDateStringNegative(): void
    {
        $past = new Chronos('2020-01-01');
        $future = new Chronos('2020-01-02');
        $diff = $future->diff($past);

        $interval = ChronosInterval::instance($diff);
        $this->assertStringStartsWith('-', $interval->toDateString());
    }

    public function testToDateStringRoundTrip(): void
    {
        $original = ChronosInterval::create('P1Y2M3D');
        $dateString = $original->toDateString();

        $recreated = ChronosInterval::createFromDateString($dateString);

        $this->assertSame($original->y, $recreated->y);
        $this->assertSame($original->m, $recreated->m);
        $this->assertSame($original->d, $recreated->d);
    }
}
