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

    public function testCreateFromFormatMissingTimeIsZero()
    {
        // Missing time components should be zero, not current time
        $d = Chronos::createFromFormat('Y-m-d', '2024-03-14');
        $this->assertDateTime($d, 2024, 3, 14, 0, 0, 0);
        $this->assertSame(0, $d->micro);
    }

    public function testCreateFromFormatMissingSecondsIsZero()
    {
        // Missing seconds should be zero
        $d = Chronos::createFromFormat('Y-m-d H:i', '2024-03-14 12:30');
        $this->assertDateTime($d, 2024, 3, 14, 12, 30, 0);
    }

    public function testCreateFromFormatMissingDateIsEpoch()
    {
        // Missing date components should be Unix epoch (1970-01-01)
        $d = Chronos::createFromFormat('H:i:s', '12:30:45');
        $this->assertDateTime($d, 1970, 1, 1, 12, 30, 45);
    }

    public function testCreateFromFormatMissingMicrosecondsIsZero()
    {
        // Missing microseconds should be zero
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '2024-03-14 12:30:45');
        $this->assertSame(0, $d->micro);
    }

    public function testCreateFromFormatWithExplicitPipeModifier()
    {
        // Explicit | should still work
        $d = Chronos::createFromFormat('Y-m-d|', '2024-03-14');
        $this->assertDateTime($d, 2024, 3, 14, 0, 0, 0);
    }

    public function testCreateFromFormatWithExplicitBangModifier()
    {
        // Explicit ! should still work
        $d = Chronos::createFromFormat('!Y-m-d', '2024-03-14');
        $this->assertDateTime($d, 2024, 3, 14, 0, 0, 0);
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

    public function testCreateFromFormatWithUnixTimestamp()
    {
        $d = Chronos::createFromFormat('U', '0');
        $this->assertDateTime($d, 1970, 1, 1, 0, 0, 0);
    }

    public function testCreateFromFormatWithNegativeUnixTimestamp()
    {
        $d = Chronos::createFromFormat('U', '-1000');
        $this->assertDateTime($d, 1969, 12, 31, 23, 43, 20);
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

    public function testCreateFromFormatDoesNotUseTestNow()
    {
        // testNow should not affect createFromFormat - missing components are zero
        Chronos::setTestNow(new Chronos('2020-12-01 14:30:45'));
        $d = Chronos::createFromFormat('Y-m-d', '2024-03-14');
        $this->assertDateTime($d, 2024, 3, 14, 0, 0, 0);
    }
}
