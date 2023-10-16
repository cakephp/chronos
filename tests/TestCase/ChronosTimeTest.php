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
use DateTimeImmutable;
use InvalidArgumentException;

class ChronosTimeTest extends TestCase
{
    public function testConstructNow(): void
    {
        Chronos::setTestNow('2001-01-01 12:13:14.123456');

        $t = new ChronosTime();
        $this->assertSame('12:13:14.123456', $t->format('H:i:s.u'));

        $t = new ChronosTime(null);
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
}
