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
use Cake\Chronos\ChronosTime;
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

class ChronosTimeTest extends TestCase
{
    public function testConstructNow(): void
    {
        Chronos::setTestNow('2001-01-01 12:13:14.123456');

        $t = new ChronosTime();
        $this->assertSame('12:13:14.123456', $t->format('H:i:s.u'));

        $t = new ChronosTime();
        $this->assertSame('12:13:14.123456', $t->format('H:i:s.u'));
    }

    public function testConstructFromString(): void
    {
        $t = new ChronosTime('12:13');
        $this->assertSame('12:13:00.000000', $t->format('H:i:s.u'));

        $t = new ChronosTime('0.0.0.0');
        $this->assertSame('00:00:00.000000', $t->format('H:i:s.u'));

        $t = new ChronosTime('1:01:1.000001');
        $this->assertSame('01:01:01.000001', $t->format('H:i:s.u'));

        $t = new ChronosTime('23:59.59.999999');
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        $t = new ChronosTime('23:59.59.9999991');
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        $t = new ChronosTime('24:59.59.9999991');
        $this->assertSame('00:59:59.999999', $t->format('H:i:s.u'));
    }

    public function testConstructFromInstance(): void
    {
        $t = new ChronosTime(new DateTimeImmutable('23:59:59.999999'));
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        $t = new ChronosTime(new Chronos('23:59:59.999999'));
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        $t = new ChronosTime(new ChronosTime(new Chronos('23:59:59.999999')));
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));
    }

    public function testConstructInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('tomorrow');
    }

    public function testConstructIncomplete(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('23');
    }

    public function testConstructInvalidHours(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('25:00:00');
    }

    public function testConstructInvalidMinutes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('23:60:00');
    }

    public function testConstructInvalidSeconds(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('23:59:60');
    }

    public function testParse(): void
    {
        $t = ChronosTime::parse('23:59:59.999999');
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        Chronos::setTestNow(new Chronos('2001-01-01 12:13:14.123456', 'America/Chicago'));
        $t = ChronosTime::parse(null, 'America/New_York');
        $this->assertSame('13:13:14.123456', $t->format('H:i:s.u'));
    }

    public function testNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:13:14.123456', 'America/Chicago'));

        $t = ChronosTime::now();
        $this->assertSame('12:13:14.123456', $t->format('H:i:s.u'));

        $t = ChronosTime::now('America/New_York');
        $this->assertSame('13:13:14.123456', $t->format('H:i:s.u'));
    }

    public function testMidnight(): void
    {
        $t = ChronosTime::midnight();
        $this->assertSame('00:00:00.000000', $t->format('H:i:s.u'));
    }

    public function testNoon(): void
    {
        $t = ChronosTime::noon();
        $this->assertSame('12:00:00.000000', $t->format('H:i:s.u'));
    }

    public function testEndOfDay(): void
    {
        $t = ChronosTime::endOfDay();
        $this->assertSame('23:59:59.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::endOfDay(microseconds: true);
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));
    }

    public function testSetters(): void
    {
        $t = ChronosTime::midnight()->setHours(24);
        $this->assertSame('00:00:00.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setHours(-1);
        $this->assertSame('23:00:00.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setMinutes(60);
        $this->assertSame('01:00:00.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setMinutes(-1);
        $this->assertSame('23:59:00.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setSeconds(60);
        $this->assertSame('00:01:00.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setSeconds(-1);
        $this->assertSame('23:59:59.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setMicroseconds(1_000_000);
        $this->assertSame('00:00:01.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setMicroseconds(-1);
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setHours(25)->setMinutes(120)->setSeconds(120)->setMicroseconds(2_000_001);
        $this->assertSame('03:02:02.000001', $t->format('H:i:s.u'));
    }

    public function testGetters(): void
    {
        $t = ChronosTime::midnight()->setTime(-1, -1, -1, -1);
        $this->assertSame(22, $t->getHours());
        $this->assertSame(58, $t->getMinutes());
        $this->assertSame(58, $t->getSeconds());
        $this->assertSame(999_999, $t->getMicroseconds());
    }

    public function testSetTime(): void
    {
        $t = new ChronosTime();
        $new = $t->setTime();
        $this->assertNotSame($new, $t);
        $this->assertSame('00:00:00.000000', $new->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setTime(24, 120, 120, 1_000_000);
        $this->assertSame('02:02:01.000000', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setTime(-1, -1, -1, -1);
        $this->assertSame('22:58:58.999999', $t->format('H:i:s.u'));

        $t = ChronosTime::midnight()->setTime(-1, 120, -1, 1_000_001);
        $this->assertSame('01:00:00.000001', $t->format('H:i:s.u'));
    }

    public function testStartOfHour(): void
    {
        $t = ChronosTime::parse('12:30:45.123456');
        $start = $t->startOfHour();

        $this->assertNotSame($t, $start);
        $this->assertSame('12:00:00.000000', $start->format('H:i:s.u'));

        $t = ChronosTime::parse('00:59:59.999999');
        $this->assertSame('00:00:00.000000', $t->startOfHour()->format('H:i:s.u'));

        $t = ChronosTime::parse('23:01:01');
        $this->assertSame('23:00:00.000000', $t->startOfHour()->format('H:i:s.u'));
    }

    public function testEndOfHour(): void
    {
        $t = ChronosTime::parse('12:30:45.123456');
        $end = $t->endOfHour();

        $this->assertNotSame($t, $end);
        $this->assertSame('12:59:59.999999', $end->format('H:i:s.u'));

        $t = ChronosTime::parse('00:00:00');
        $this->assertSame('00:59:59.999999', $t->endOfHour()->format('H:i:s.u'));

        $t = ChronosTime::parse('23:30:00');
        $this->assertSame('23:59:59.999999', $t->endOfHour()->format('H:i:s.u'));
    }

    public function testFormat(): void
    {
        $t = new ChronosTime('23:59:59.999999');
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));
    }

    public function testComparisons(): void
    {
        $t1 = ChronosTime::parse('00:00:00');
        $t2 = ChronosTime::parse('00:00:00');
        $this->assertTrue($t1->equals($t2));
        $this->assertTrue($t1->greaterThanOrEquals($t2));
        $this->assertTrue($t1->lessThanOrEquals($t2));
        $this->assertFalse($t1->greaterThan($t2));
        $this->assertFalse($t1->lessThan($t2));

        $t1 = ChronosTime::parse('00:00:00');
        $t2 = ChronosTime::parse('00:00:00')->setHours(24);
        $this->assertTrue($t1->equals($t2));
        $this->assertTrue($t1->greaterThanOrEquals($t2));
        $this->assertTrue($t1->lessThanOrEquals($t2));
        $this->assertFalse($t1->greaterThan($t2));
        $this->assertFalse($t1->lessThan($t2));

        $t1 = ChronosTime::parse('00:00:00');
        $t2 = ChronosTime::parse('00:00:00.000001');
        $this->assertTrue($t1->lessThan($t2));
        $this->assertTrue($t1->lessThanOrEquals($t2));
        $this->assertFalse($t1->equals($t2));
        $this->assertFalse($t1->greaterThan($t2));
        $this->assertFalse($t1->greaterThanOrEquals($t2));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00');
        $this->assertTrue($t1->greaterThan($t2));
        $this->assertTrue($t1->greaterThanOrEquals($t2));
        $this->assertFalse($t1->equals($t2));
        $this->assertFalse($t1->lessThan($t2));
        $this->assertFalse($t1->lessThanOrEquals($t2));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertTrue($t2->between($t1, $t3));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertTrue($t2->between($t1, $t3, false));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertTrue($t1->between($t1, $t3, true));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertFalse($t1->between($t1, $t3, false));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertTrue($t2->between($t3, $t1, false));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertFalse($t1->between($t2, $t3));

        $t1 = ChronosTime::parse('00:00:00.000001');
        $t2 = ChronosTime::parse('00:00:00.000002');
        $t3 = ChronosTime::parse('00:00:00.000003');
        $this->assertFalse($t3->between($t1, $t2));
    }

    public function testIsStartOfDay(): void
    {
        $this->assertTrue(ChronosTime::parse('00:00:00')->isStartOfDay());
        $this->assertTrue(ChronosTime::midnight()->isStartOfDay());
        $this->assertFalse(ChronosTime::parse('00:00:00.000001')->isStartOfDay());
        $this->assertFalse(ChronosTime::parse('00:00:01')->isStartOfDay());
        $this->assertFalse(ChronosTime::noon()->isStartOfDay());
    }

    public function testIsEndOfDay(): void
    {
        $this->assertTrue(ChronosTime::parse('23:59:59')->isEndOfDay());
        $this->assertTrue(ChronosTime::endOfDay()->isEndOfDay());
        $this->assertTrue(ChronosTime::parse('23:59:59.999999')->isEndOfDay());
        $this->assertFalse(ChronosTime::parse('23:59:58')->isEndOfDay());
        $this->assertFalse(ChronosTime::midnight()->isEndOfDay());
        $this->assertFalse(ChronosTime::noon()->isEndOfDay());
    }

    public function testIsMidnight(): void
    {
        $this->assertTrue(ChronosTime::midnight()->isMidnight());
        $this->assertTrue(ChronosTime::parse('00:00:00')->isMidnight());
        $this->assertFalse(ChronosTime::parse('00:00:00.000001')->isMidnight());
        $this->assertFalse(ChronosTime::noon()->isMidnight());
    }

    public function testIsMidday(): void
    {
        $this->assertTrue(ChronosTime::noon()->isMidday());
        $this->assertTrue(ChronosTime::parse('12:00:00')->isMidday());
        $this->assertFalse(ChronosTime::parse('12:00:00.000001')->isMidday());
        $this->assertFalse(ChronosTime::parse('12:00:01')->isMidday());
        $this->assertFalse(ChronosTime::midnight()->isMidday());
    }

    public function testToDateTimeImmutable(): void
    {
        $time = ChronosTime::parse('23:59:59.999999');
        $native = $time->toDateTimeImmutable();
        $this->assertSame('23:59:59.999999', $native->format('H:i:s.u'));

        $native = $time->toNative();
        $this->assertSame('23:59:59.999999', $native->format('H:i:s.u'));

        $native = $time->toDateTimeImmutable('Asia/Tokyo');
        $this->assertSame('23:59:59.999999', $native->format('H:i:s.u'));
        $this->assertSame('Asia/Tokyo', $native->getTimezone()->getName());

        $native = $time->toNative('Asia/Tokyo');
        $this->assertSame('23:59:59.999999', $native->format('H:i:s.u'));
        $this->assertSame('Asia/Tokyo', $native->getTimezone()->getName());
    }

    public function testToString(): void
    {
        $t = new ChronosTime('12:13:14.123456');
        $this->assertSame('12:13:14', (string)$t);

        ChronosTime::setToStringFormat('H:i:s.u');
        $this->assertSame('12:13:14.123456', (string)$t);

        ChronosTime::resetToStringFormat();
        $this->assertSame('12:13:14', (string)$t);
    }

    public function testToArray(): void
    {
        $t = new ChronosTime('12:30:45.123456');
        $array = $t->toArray();

        $this->assertSame(12, $array['hour']);
        $this->assertSame(30, $array['minute']);
        $this->assertSame(45, $array['second']);
        $this->assertSame(123456, $array['microsecond']);
        $this->assertCount(4, $array);
    }

    public function testAddHours(): void
    {
        $t = ChronosTime::parse('10:20:30.123456');
        $new = $t->addHours(2);

        $this->assertNotSame($t, $new);
        $this->assertSame('10:20:30.123456', $t->format('H:i:s.u'));
        $this->assertSame('12:20:30.123456', $new->format('H:i:s.u'));

        // Wraparound past midnight.
        $this->assertSame(
            '01:00:00.000000',
            ChronosTime::parse('23:00:00')->addHours(2)->format('H:i:s.u'),
        );

        // Negative addition.
        $this->assertSame(
            '22:00:00.000000',
            ChronosTime::parse('00:00:00')->addHours(-2)->format('H:i:s.u'),
        );
    }

    public function testSubHours(): void
    {
        $t = ChronosTime::parse('10:20:30.123456');
        $new = $t->subHours(2);

        $this->assertNotSame($t, $new);
        $this->assertSame('08:20:30.123456', $new->format('H:i:s.u'));

        // Wraparound before midnight.
        $this->assertSame(
            '23:00:00.000000',
            ChronosTime::parse('01:00:00')->subHours(2)->format('H:i:s.u'),
        );
    }

    public function testAddMinutes(): void
    {
        $this->assertSame(
            '10:25:00.000000',
            ChronosTime::parse('10:20:00')->addMinutes(5)->format('H:i:s.u'),
        );
        $this->assertSame(
            '00:05:00.000000',
            ChronosTime::parse('23:55:00')->addMinutes(10)->format('H:i:s.u'),
        );
    }

    public function testSubMinutes(): void
    {
        $this->assertSame(
            '10:15:00.000000',
            ChronosTime::parse('10:20:00')->subMinutes(5)->format('H:i:s.u'),
        );
        $this->assertSame(
            '23:55:00.000000',
            ChronosTime::parse('00:05:00')->subMinutes(10)->format('H:i:s.u'),
        );
    }

    public function testAddSeconds(): void
    {
        $this->assertSame(
            '10:20:35.000000',
            ChronosTime::parse('10:20:30')->addSeconds(5)->format('H:i:s.u'),
        );
        $this->assertSame(
            '00:00:05.000000',
            ChronosTime::parse('23:59:55')->addSeconds(10)->format('H:i:s.u'),
        );
    }

    public function testSubSeconds(): void
    {
        $this->assertSame(
            '10:20:25.000000',
            ChronosTime::parse('10:20:30')->subSeconds(5)->format('H:i:s.u'),
        );
        $this->assertSame(
            '23:59:55.000000',
            ChronosTime::parse('00:00:05')->subSeconds(10)->format('H:i:s.u'),
        );
    }

    public function testModify(): void
    {
        $t = ChronosTime::parse('10:20:30.123456');
        $new = $t->modify('+2 hours');

        $this->assertNotSame($t, $new);
        $this->assertSame('10:20:30.123456', $t->format('H:i:s.u'));
        $this->assertSame('12:20:30.123456', $new->format('H:i:s.u'));

        $this->assertSame(
            '10:25:30.123456',
            ChronosTime::parse('10:20:30.123456')->modify('+5 minutes')->format('H:i:s.u'),
        );
        $this->assertSame(
            '10:20:35.123456',
            ChronosTime::parse('10:20:30.123456')->modify('+5 seconds')->format('H:i:s.u'),
        );

        // Wraparound past midnight.
        $this->assertSame(
            '01:00:00.000000',
            ChronosTime::parse('23:00:00')->modify('+2 hours')->format('H:i:s.u'),
        );
    }

    public function testModifyInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ChronosTime::parse('10:00:00')->modify('+1 day');
    }

    public function testModifyInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ChronosTime::parse('10:00:00')->modify('not a valid modifier');
    }

    public function testSecondsSinceMidnight(): void
    {
        $this->assertSame(0, ChronosTime::midnight()->secondsSinceMidnight());
        $this->assertSame(
            12 * 3600 + 30 * 60 + 45,
            ChronosTime::parse('12:30:45')->secondsSinceMidnight(),
        );
        $this->assertSame(
            23 * 3600 + 59 * 60 + 59,
            ChronosTime::parse('23:59:59.999999')->secondsSinceMidnight(),
        );
    }

    public function testDiff(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('12:30:45');
        $interval = $t1->diff($t2);

        $this->assertInstanceOf(DateInterval::class, $interval);
        $this->assertSame(2, $interval->h);
        $this->assertSame(30, $interval->i);
        $this->assertSame(45, $interval->s);
        $this->assertSame(0, $interval->invert);

        // Reverse order keeps sign by default (matches DateTimeInterface::diff).
        $interval = $t2->diff($t1);
        $this->assertSame(2, $interval->h);
        $this->assertSame(30, $interval->i);
        $this->assertSame(45, $interval->s);
        $this->assertSame(1, $interval->invert);

        // Absolute mode drops the sign.
        $interval = $t2->diff($t1, true);
        $this->assertSame(2, $interval->h);
        $this->assertSame(30, $interval->i);
        $this->assertSame(45, $interval->s);
        $this->assertSame(0, $interval->invert);
    }

    public function testDiffInHours(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('12:30:00');

        $this->assertSame(2, $t1->diffInHours($t2));
        $this->assertSame(2, $t2->diffInHours($t1));
        $this->assertSame(-2, $t2->diffInHours($t1, false));
        $this->assertSame(2, $t1->diffInHours($t2, false));
    }

    public function testDiffInMinutes(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('10:05:30');

        $this->assertSame(5, $t1->diffInMinutes($t2));
        $this->assertSame(5, $t2->diffInMinutes($t1));
        $this->assertSame(-5, $t2->diffInMinutes($t1, false));
    }

    public function testDiffInSeconds(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('10:00:45');

        $this->assertSame(45, $t1->diffInSeconds($t2));
        $this->assertSame(45, $t2->diffInSeconds($t1));
        $this->assertSame(-45, $t2->diffInSeconds($t1, false));
    }

    public function testClosest(): void
    {
        $base = ChronosTime::parse('10:00:00');
        $a = ChronosTime::parse('09:50:00');
        $b = ChronosTime::parse('10:30:00');
        $c = ChronosTime::parse('11:00:00');

        $this->assertTrue($a->equals($base->closest($a, $b, $c)));
    }

    public function testClosestFirstWinsOnTie(): void
    {
        $base = ChronosTime::parse('10:00:00');
        $before = ChronosTime::parse('09:30:00');
        $after = ChronosTime::parse('10:30:00');

        // Equal distance — first argument wins.
        $this->assertTrue($before->equals($base->closest($before, $after)));
    }

    public function testFarthest(): void
    {
        $base = ChronosTime::parse('10:00:00');
        $a = ChronosTime::parse('09:50:00');
        $b = ChronosTime::parse('10:30:00');
        $c = ChronosTime::parse('12:00:00');

        $this->assertTrue($c->equals($base->farthest($a, $b, $c)));
    }

    public function testFarthestFirstWinsOnTie(): void
    {
        $base = ChronosTime::parse('10:00:00');
        $before = ChronosTime::parse('09:00:00');
        $after = ChronosTime::parse('11:00:00');

        $this->assertTrue($before->equals($base->farthest($before, $after)));
    }

    public function testMin(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('12:00:00');

        $this->assertTrue($t1->equals($t1->min($t2)));
        $this->assertTrue($t1->equals($t2->min($t1)));
    }

    public function testMinDefaultsToNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:00:00'));

        $earlier = ChronosTime::parse('10:00:00');
        $later = ChronosTime::parse('14:00:00');

        // $earlier vs now (12:00) → earlier is smaller.
        $this->assertTrue($earlier->equals($earlier->min()));
        // $later vs now (12:00) → now is smaller.
        $this->assertTrue(ChronosTime::parse('12:00:00')->equals($later->min()));
    }

    public function testMax(): void
    {
        $t1 = ChronosTime::parse('10:00:00');
        $t2 = ChronosTime::parse('12:00:00');

        $this->assertTrue($t2->equals($t1->max($t2)));
        $this->assertTrue($t2->equals($t2->max($t1)));
    }

    public function testMaxDefaultsToNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:00:00'));

        $earlier = ChronosTime::parse('10:00:00');
        $later = ChronosTime::parse('14:00:00');

        // $earlier vs now (12:00) → now is larger.
        $this->assertTrue(ChronosTime::parse('12:00:00')->equals($earlier->max()));
        // $later vs now (12:00) → later is larger.
        $this->assertTrue($later->equals($later->max()));
    }

    public function testDiffInHoursDefaultsToNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:00:00'));

        // Sign convention (matches Chronos::diffInSeconds): $other - $this.
        $this->assertSame(2, ChronosTime::parse('10:00:00')->diffInHours());
        $this->assertSame(2, ChronosTime::parse('10:00:00')->diffInHours(absolute: false));
        $this->assertSame(-3, ChronosTime::parse('15:00:00')->diffInHours(absolute: false));
    }

    public function testDiffInMinutesDefaultsToNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:00:00'));

        $this->assertSame(30, ChronosTime::parse('11:30:00')->diffInMinutes());
    }

    public function testDiffInSecondsDefaultsToNow(): void
    {
        Chronos::setTestNow(new Chronos('2001-01-01 12:00:00'));

        $this->assertSame(45, ChronosTime::parse('11:59:15')->diffInSeconds());
    }
}
