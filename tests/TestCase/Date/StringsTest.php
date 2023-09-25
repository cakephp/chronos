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
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;

class StringsTest extends TestCase
{
    /**
     * @var string
     */
    protected $tz;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
    }

    /**
     * Teardown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        date_default_timezone_set($this->tz);

        unset($this->tz);
    }

    public function testToString()
    {
        $d = ChronosDate::parse('2021-01-01');
        $this->assertSame($d->toDateString(), '' . $d);
    }

    public function testSetToStringFormat()
    {
        ChronosDate::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = ChronosDate::create(1975, 12, 25);
        $this->assertSame('25th of December, 1975 12:00:00 am', '' . $d);
    }

    public function testResetToStringFormat()
    {
        $d = ChronosDate::parse(Chronos::now());
        ChronosDate::setToStringFormat('123');
        ChronosDate::resetToStringFormat();
        $this->assertSame($d->toDateString(), '' . $d);
    }

    public function testToDateString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    public function testToFormattedDateString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    public function testToTimeString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('00:00:00', $d->toTimeString());
    }

    public function testToDateTimeString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 00:00:00', $d->toDateTimeString());
    }

    public function testToDayDateTimeString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 12:00 AM', $d->toDayDateTimeString());
    }

    public function testToAtomString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toAtomString());
    }

    public function testToCOOKIEString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        if (DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 00:00:00 UTC';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 00:00:00 UTC';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    public function testToIso8601String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toIso8601String());
    }

    public function testToRC822String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc822String());
    }

    public function testToRfc850String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 00:00:00 UTC', $d->toRfc850String());
    }

    public function testToRfc1036String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc1036String());
    }

    public function testToRfc1123String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc1123String());
    }

    public function testToRfc2822String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc2822String());
    }

    public function testToRfc3339String()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toRfc3339String());
    }

    public function testToRssString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRssString());
    }

    public function testToW3cString()
    {
        $d = ChronosDate::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toW3cString());
    }

    public function testToDateTimeImmutable(): void
    {
        $d = ChronosDate::now();
        $this->assertSame($d->format('Y-m-d H:i:s.u'), $d->toDateTimeImmutable()->format('Y-m-d H:i:s.u'));
        $this->assertSame($d->format('Y-m-d H:i:s.u'), $d->toNative()->format('Y-m-d H:i:s.u'));

        $native = $d->toDateTimeImmutable('Asia/Tokyo');
        $this->assertSame($d->format('Y-m-d H:i:s.u'), $native->format('Y-m-d H:i:s.u'));
        $this->assertSame('Asia/Tokyo', $native->getTimezone()->getName());

        $native = $d->toNative('Asia/Tokyo');
        $this->assertSame($d->format('Y-m-d H:i:s.u'), $native->format('Y-m-d H:i:s.u'));
        $this->assertSame('Asia/Tokyo', $native->getTimezone()->getName());
    }
}
