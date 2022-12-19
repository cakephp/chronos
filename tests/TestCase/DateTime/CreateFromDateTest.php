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

class CreateFromDateTest extends TestCase
{
    public function testCreateFromDateWithDefaults()
    {
        $d = Chronos::createFromDate();
        $this->assertSame($d->timestamp, Chronos::create(null, null, null, null, null, null, null)->timestamp);
    }

    public function testCreateFromDate()
    {
        $d = Chronos::createFromDate(1975, 5, 21);
        $this->assertDateTime($d, 1975, 5, 21);
    }

    public function testCreateFromDateWithYear()
    {
        $d = Chronos::createFromDate(1975);
        $this->assertSame(1975, $d->year);
    }

    public function testCreateFromDateWithMonth()
    {
        $d = Chronos::createFromDate(null, 5);
        $this->assertSame(5, $d->month);
    }

    public function testCreateFromDateWithDay()
    {
        $d = Chronos::createFromDate(null, null, 21);
        $this->assertSame(21, $d->day);
    }

    public function testCreateFromDateWithTimezone()
    {
        $d = Chronos::createFromDate(1975, 5, 21, 'Europe/London');
        $this->assertDateTime($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromDateWithDateTimeZone()
    {
        $d = Chronos::createFromDate(1975, 5, 21, new DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
