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
    public function testConstructDefault(): void
    {
        $t = new ChronosTime();
        $this->assertSame(0, $t->getMicroseconds());
        $this->assertSame(0, $t->getSeconds());
        $this->assertSame(0, $t->getMinutes());
        $this->assertSame(0, $t->getHours());

        $t = new ChronosTime(null);
        $this->assertSame(0, $t->getMicroseconds());
        $this->assertSame(0, $t->getSeconds());
        $this->assertSame(0, $t->getMinutes());
        $this->assertSame(0, $t->getHours());
    }

    public function testConstructFromString(): void
    {
        $t = new ChronosTime('0.0.0.0');
        $this->assertSame(0, $t->getMicroseconds());
        $this->assertSame(0, $t->getSeconds());
        $this->assertSame(0, $t->getMinutes());
        $this->assertSame(0, $t->getHours());

        $t = new ChronosTime('1:01:1.000001');
        $this->assertSame(1, $t->getMicroseconds());
        $this->assertSame(1, $t->getSeconds());
        $this->assertSame(1, $t->getMinutes());
        $this->assertSame(1, $t->getHours());

        $t = new ChronosTime('23:59.59.999999');
        $this->assertSame(999999, $t->getMicroseconds());
        $this->assertSame(59, $t->getSeconds());
        $this->assertSame(59, $t->getMinutes());
        $this->assertSame(23, $t->getHours());

        $t = new ChronosTime('23:59.59.9999991');
        $this->assertSame(999999, $t->getMicroseconds());
        $this->assertSame(59, $t->getSeconds());
        $this->assertSame(59, $t->getMinutes());
        $this->assertSame(23, $t->getHours());

        $t = new ChronosTime('12:13');
        $this->assertSame(0, $t->getMicroseconds());
        $this->assertSame(0, $t->getSeconds());
        $this->assertSame(13, $t->getMinutes());
        $this->assertSame(12, $t->getHours());
    }

    public function testConstructFromInstance(): void
    {
        $t = new ChronosTime(new DateTimeImmutable('23:59:59.999999'));
        $this->assertSame(999999, $t->getMicroseconds());
        $this->assertSame(59, $t->getSeconds());
        $this->assertSame(59, $t->getMinutes());
        $this->assertSame(23, $t->getHours());

        $t = new ChronosTime(new Chronos('23:59:59.999999'));
        $this->assertSame(999999, $t->getMicroseconds());
        $this->assertSame(59, $t->getSeconds());
        $this->assertSame(59, $t->getMinutes());
        $this->assertSame(23, $t->getHours());

        $t = new ChronosTime(new ChronosTime(new Chronos('23:59:59.999999')));
        $this->assertSame(999999, $t->getMicroseconds());
        $this->assertSame(59, $t->getSeconds());
        $this->assertSame(59, $t->getMinutes());
        $this->assertSame(23, $t->getHours());
    }

    public function testConstructInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('now');
    }

    public function testConstructIncomplete(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('23');
    }

    public function testConstructInvalidHours(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChronosTime('24:00:00');
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

    public function testSetTime(): void
    {
        $t = new ChronosTime();
        $new = $t->setTime();
        $this->assertNotSame($new, $t);
        $this->assertSame(0, $new->getHours());
        $this->assertSame(0, $new->getMinutes());
        $this->assertSame(0, $new->getSeconds());
        $this->assertSame(0, $new->getMicroseconds());

        $t = new ChronosTime('23:59.59.9999991');

        $new = $t->setHours(24);
        $this->assertSame(0, $new->getHours());

        $new = $t->setHours(-1);
        $this->assertSame(23, $new->getHours());

        $new = $t->setMinutes(60);
        $this->assertSame(0, $new->getMinutes());

        $new = $t->setMinutes(-1);
        $this->assertSame(59, $new->getMinutes());

        $new = $t->setSeconds(60);
        $this->assertSame(0, $new->getSeconds());

        $new = $t->setSeconds(-1);
        $this->assertSame(59, $new->getSeconds());

        $new = $t->setMicroseconds(1_000_000);
        $this->assertSame(0, $new->getMicroseconds());

        $new = $t->setMicroseconds(-1);
        $this->assertSame(999_999, $new->getMicroseconds());

        $new = $t->setTime(24, 60, 60, 1_000_000);
        $this->assertSame(0, $new->getHours());
        $this->assertSame(0, $new->getMinutes());
        $this->assertSame(0, $new->getSeconds());
        $this->assertSame(0, $new->getMicroseconds());

        $new = $t->setTime(-1, -1, -1, -1);
        $this->assertSame(23, $new->getHours());
        $this->assertSame(59, $new->getMinutes());
        $this->assertSame(59, $new->getSeconds());
        $this->assertSame(999_999, $new->getMicroseconds());
    }

    public function testFormat(): void
    {
        $t = new ChronosTime('23:59:59.999999');
        $this->assertSame('23:59:59.999999', $t->format('H:i:s.u'));
    }
}
