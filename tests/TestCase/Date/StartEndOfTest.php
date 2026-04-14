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

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

class StartEndOfTest extends TestCase
{
    public function testStartOfMonthIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->startOfMonth();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testStartOfMonthFromNow(): void
    {
        $dt = ChronosDate::parse(Chronos::now())->startOfMonth();
        $this->assertDateTime($dt, $dt->year, $dt->month, 1);
    }

    public function testStartOfMonthFromLastDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 31)->startOfMonth();
        $this->assertDateTime($dt, 2000, 1, 1);
    }

    public function testStartOfYearIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->startOfYear();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testStartOfYearFromNow(): void
    {
        $dt = ChronosDate::parse(Chronos::now())->startOfYear();
        $this->assertDateTime($dt, $dt->year, 1, 1);
    }

    public function testStartOfYearFromFirstDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 1)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1);
    }

    public function testStartOfYearFromLastDay(): void
    {
        $dt = ChronosDate::create(2000, 12, 31)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1);
    }

    public function testEndOfMonthIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->endOfMonth();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testEndOfMonth(): void
    {
        $dt = ChronosDate::create(2000, 1, 1)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31);
    }

    public function testEndOfMonthFromLastDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 31)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31);
    }

    public function testEndOfYearIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->endOfYear();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testEndOfYearFromNow(): void
    {
        $dt = ChronosDate::parse(Chronos::now())->endOfYear();
        $this->assertDateTime($dt, $dt->year, 12, 31);
    }

    public function testEndOfYearFromFirstDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 1)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31);
    }

    public function testEndOfYearFromLastDay(): void
    {
        $dt = ChronosDate::create(2000, 12, 31)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31);
    }

    public function testStartOfDecadeIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->startOfDecade();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testStartOfDecadeFromNow(): void
    {
        $dt = ChronosDate::parse(Chronos::now())->startOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10, 1, 1);
    }

    public function testStartOfDecadeFromFirstDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 1)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1);
    }

    public function testStartOfDecadeFromLastDay(): void
    {
        $dt = ChronosDate::create(2009, 12, 31)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1);
    }

    public function testEndOfDecadeIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->endOfDecade();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testEndOfDecadeFromNow(): void
    {
        $dt = ChronosDate::parse(Chronos::now())->endOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10 + 9, 12, 31);
    }

    public function testEndOfDecadeFromFirstDay(): void
    {
        $dt = ChronosDate::create(2000, 1, 1)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31);
    }

    public function testEndOfDecadeFromLastDay(): void
    {
        $dt = ChronosDate::create(2009, 12, 31)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31);
    }

    public function testStartOfCenturyIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->startOfCentury();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testStartOfCenturyFromNow(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = ChronosDate::parse(Chronos::now())->startOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 1, 1, 1);
    }

    public function testStartOfCenturyFromFirstDay(): void
    {
        $dt = ChronosDate::create(2001, 1, 1)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1);
    }

    public function testStartOfCenturyFromLastDay(): void
    {
        $dt = ChronosDate::create(2100, 12, 31)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1);
    }

    public function testEndOfCenturyIsFluid(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = $now->endOfCentury();
        $this->assertTrue($dt instanceof ChronosDate);
    }

    public function testEndOfCenturyFromNow(): void
    {
        $now = ChronosDate::parse(Chronos::now());
        $dt = ChronosDate::parse(Chronos::now())->endOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 100, 12, 31);
    }

    public function testEndOfCenturyFromFirstDay(): void
    {
        $dt = ChronosDate::create(2001, 1, 1)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31);
    }

    public function testEndOfCenturyFromLastDay(): void
    {
        $dt = ChronosDate::create(2100, 12, 31)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31);
    }
}
