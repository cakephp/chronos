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
use DateTimeZone;
use InvalidArgumentException;

class CreateFromFormatTest extends TestCase
{
    public function testCreateFromFormatReturnsInstance()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertTrue($d instanceof Chronos);
    }

    public function testCreateFromFormatWithTestNowMissingYear()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('m-d H:i:s', '10-05 09:15:30');
        $this->assertDateTime($d, 2020, 10, 5, 9, 15, 30);
    }

    public function testCreateFromFormatWithTestNowMissingDate()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('H:i:s', '09:15:30');
        $this->assertDateTime($d, 2020, 12, 1, 9, 15, 30);
    }

    public function testCreateFromFormatWithTestNowMissingTime()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('Y-m-d', '2021-06-15');
        $this->assertDateTime($d, 2021, 6, 15, 14, 30, 45);
    }

    public function testCreateFromFormatWithTestNowPartialDate()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 00:00:00'));
        $d = Chronos::createFromFormat('m-d', '10-05');
        $this->assertDateTime($d, 2020, 10, 5, 0, 0, 0);
    }

    public function testCreateFromFormatWithTestNowDayOnly()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 00:00:00'));
        $d = Chronos::createFromFormat('d', '05');
        $this->assertDateTime($d, 2020, 12, 5, 0, 0, 0);
    }

    public function testCreateFromFormatWithTestNowComplete()
    {
        // When format is complete, testNow should not affect the result
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
    }

    public function testCreateFromFormatWithTestNowResetModifier()
    {
        // The '!' modifier resets to Unix epoch, should not use testNow
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('!Y-m-d', '2021-06-15');
        $this->assertDateTime($d, 2021, 6, 15, 0, 0, 0);
    }

    public function testCreateFromFormatWithTestNowPipeModifier()
    {
        // The '|' modifier resets unspecified components to zero, should not use testNow
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('Y-m-d|', '2021-06-15');
        $this->assertDateTime($d, 2021, 6, 15, 0, 0, 0);
    }

    public function testCreateFromFormatWithoutTestNow()
    {
        // Without testNow set, behavior should use real current time for missing components
        Chronos::setTestNow(null);
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
    }

    public function testCreateFromFormatWithTestNowEscapedCharacters()
    {
        // Escaped format characters should not be treated as format specifiers
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('\Y\-m-d', 'Y-10-05');
        $this->assertDateTime($d, 2020, 10, 5, 14, 30, 45);
    }

    public function testCreateFromFormatWithTestNowMicroseconds()
    {
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45.123456'));
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '2021-06-15 09:15:30');
        $this->assertSame(123456, $d->micro);
    }

    public function testCreateFromFormatWithTimezoneString()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', 'Europe/London');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromFormatWithTimezone()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', new DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromFormatWithMillis()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s.u', '1975-05-21 22:32:11.254687');
        $this->assertSame(254687, $d->micro);
    }

    public function testCreateFromFormatInvalidFormat()
    {
        $parseException = null;
        try {
            Chronos::createFromFormat('Y-m-d H:i:s.u', '1975-05-21');
        } catch (InvalidArgumentException $e) {
            $parseException = $e;
        }

        $this->assertNotNull($parseException);
        $this->assertIsArray(Chronos::getLastErrors());
        $this->assertNotEmpty(Chronos::getLastErrors()['errors']);
    }
}
