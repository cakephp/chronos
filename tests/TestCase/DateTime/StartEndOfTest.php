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

class StartEndOfTest extends TestCase
{
    public function testStartOfDay(): void
    {
        $now = Chronos::now();
        $dt = $now->startOfDay();
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 0, 0, 0);
    }

    public function testEndOfDay(): void
    {
        $now = Chronos::now();
        $dt = $now->endOfDay();
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 23, 59, 59, 0);

        $dt = $now->endOfDay(true);
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 23, 59, 59, 999999);
    }

    public function testStartOfMonthIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->startOfMonth();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfMonthFromNow(): void
    {
        $dt = Chronos::now()->startOfMonth();
        $this->assertDateTime($dt, $dt->year, $dt->month, 1, 0, 0, 0);
    }

    public function testStartOfMonthFromLastDay(): void
    {
        $dt = Chronos::create(2000, 1, 31, 2, 3, 4)->startOfMonth();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->startOfYear();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfYearFromNow(): void
    {
        $dt = Chronos::now()->startOfYear();
        $this->assertDateTime($dt, $dt->year, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromFirstDay(): void
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromLastDay(): void
    {
        $dt = Chronos::create(2000, 12, 31, 23, 59, 59)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfMonthIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->endOfMonth();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfMonth(): void
    {
        $dt = Chronos::create(2000, 1, 1, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfMonthFromLastDay(): void
    {
        $dt = Chronos::create(2000, 1, 31, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfYearIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->endOfYear();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfYearFromNow(): void
    {
        $dt = Chronos::now()->endOfYear();
        $this->assertDateTime($dt, $dt->year, 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromFirstDay(): void
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromLastDay(): void
    {
        $dt = Chronos::create(2000, 12, 31, 23, 59, 59)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testStartOfDecadeIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->startOfDecade();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfDecadeFromNow(): void
    {
        $dt = Chronos::now()->startOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromFirstDay(): void
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromLastDay(): void
    {
        $dt = Chronos::create(2009, 12, 31, 23, 59, 59)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfDecadeIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->endOfDecade();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfDecadeFromNow(): void
    {
        $dt = Chronos::now()->endOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10 + 9, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromFirstDay(): void
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromLastDay(): void
    {
        $dt = Chronos::create(2009, 12, 31, 23, 59, 59)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testStartOfCenturyIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->startOfCentury();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfCenturyFromNow(): void
    {
        $now = Chronos::now();
        $dt = Chronos::now()->startOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 1, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromFirstDay(): void
    {
        $dt = Chronos::create(2001, 1, 1, 1, 1, 1)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromLastDay(): void
    {
        $dt = Chronos::create(2100, 12, 31, 23, 59, 59)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    public function testEndOfCenturyIsFluid(): void
    {
        $now = Chronos::now();
        $dt = $now->endOfCentury();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfCenturyFromNow(): void
    {
        $now = Chronos::now();
        $dt = Chronos::now()->endOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 100, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromFirstDay(): void
    {
        $dt = Chronos::create(2001, 1, 1, 1, 1, 1)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromLastDay(): void
    {
        $dt = Chronos::create(2100, 12, 31, 23, 59, 59)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    public function testAverageIsFluid(): void
    {
        $dt = Chronos::now()->average();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testAverageFromSame(): void
    {
        $dt1 = Chronos::create(2000, 1, 31, 2, 3, 4);
        $dt2 = Chronos::create(2000, 1, 31, 2, 3, 4)->average($dt1);
        $this->assertDateTime($dt2, 2000, 1, 31, 2, 3, 4);
    }

    public function testAverageFromGreater(): void
    {
        $dt1 = Chronos::create(2000, 1, 1, 1, 1, 1);
        $dt2 = Chronos::create(2009, 12, 31, 23, 59, 59)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }

    public function testAverageFromLower(): void
    {
        $dt1 = Chronos::create(2009, 12, 31, 23, 59, 59);
        $dt2 = Chronos::create(2000, 1, 1, 1, 1, 1)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }
}
