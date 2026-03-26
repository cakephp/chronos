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

class InstanceTest extends TestCase
{
    public function testInstanceFromDateTime()
    {
        $dating = Chronos::instance(DateTime::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11'));
        $this->assertDateTime($dating, 1975, 5, 21, 22, 32, 11);
    }

    public function testInstanceFromDateTimeKeepsTimezoneName()
    {
        $dating = Chronos::instance(DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '1975-05-21 22:32:11',
        )->setTimezone(new DateTimeZone('America/Vancouver')));
        $this->assertSame('America/Vancouver', $dating->tzName);
    }

    public function testInstanceFromDateTimeKeepsMicros()
    {
        $micro = 254687;
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.' . $micro);
        $carbon = Chronos::instance($datetime);
        $this->assertSame($micro, $carbon->micro);
    }

    public function testShiftTimezone(): void
    {
        $dt = Chronos::create(2024, 6, 15, 10, 30, 0, 0, 'America/New_York');
        $shifted = $dt->shiftTimezone('America/Chicago');

        // Same wall clock time
        $this->assertSame(10, $shifted->hour);
        $this->assertSame(30, $shifted->minute);
        $this->assertSame(0, $shifted->second);

        // Different timezone
        $this->assertSame('America/Chicago', $shifted->tzName);

        // Different UTC time (Chicago is 1 hour behind NY in summer)
        $this->assertNotEquals($dt->getTimestamp(), $shifted->getTimestamp());
    }

    public function testShiftTimezoneVsSetTimezone(): void
    {
        $dt = Chronos::create(2024, 6, 15, 10, 0, 0, 0, 'America/New_York');

        // setTimezone converts - same moment, different wall clock
        $converted = $dt->setTimezone('America/Chicago');
        $this->assertSame(9, $converted->hour);
        $this->assertSame($dt->getTimestamp(), $converted->getTimestamp());

        // shiftTimezone keeps wall clock - different moment
        $shifted = $dt->shiftTimezone('America/Chicago');
        $this->assertSame(10, $shifted->hour);
        $this->assertNotEquals($dt->getTimestamp(), $shifted->getTimestamp());
    }

    public function testShiftTimezonePreservesMicroseconds(): void
    {
        $dt = Chronos::create(2024, 6, 15, 10, 30, 45, 123456, 'America/New_York');
        $shifted = $dt->shiftTimezone('Europe/London');

        $this->assertSame(123456, $shifted->microsecond);
        $this->assertSame(45, $shifted->second);
    }
}
