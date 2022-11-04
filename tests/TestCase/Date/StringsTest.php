<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Date;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;

class StringsTest extends TestCase
{
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
        $d = Date::now();
        $this->assertSame(Date::now()->toDateString(), '' . $d);
    }

    public function testSetToStringFormat()
    {
        Date::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = Date::create(1975, 12, 25);
        $this->assertSame('25th of December, 1975 12:00:00 am', '' . $d);
    }

    public function testResetToStringFormat()
    {
        $d = Date::now();
        Date::setToStringFormat('123');
        Date::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), '' . $d);
    }

    public function testToDateString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    public function testToFormattedDateString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    public function testToTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('00:00:00', $d->toTimeString());
    }

    public function testToDateTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 00:00:00', $d->toDateTimeString());
    }

    public function testToDayDateTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 12:00 AM', $d->toDayDateTimeString());
    }

    public function testToAtomString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toAtomString());
    }

    public function testToCOOKIEString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        if (DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 00:00:00 UTC';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 00:00:00 UTC';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    public function testToIso8601String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toIso8601String());
    }

    public function testToRC822String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc822String());
    }

    public function testToRfc850String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 00:00:00 UTC', $d->toRfc850String());
    }

    public function testToRfc1036String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc1036String());
    }

    public function testToRfc1123String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc1123String());
    }

    public function testToRfc2822String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc2822String());
    }

    public function testToRfc3339String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toRfc3339String());
    }

    public function testToRssString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRssString());
    }

    public function testToW3cString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toW3cString());
    }
}
