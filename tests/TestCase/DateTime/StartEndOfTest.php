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
    public function testStartOfDay()
    {
        $now = Chronos::now();
        $dt = $now->startOfDay();
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 0, 0, 0);
    }

    public function testEndOfDay()
    {
        $now = Chronos::now();
        $dt = $now->endOfDay();
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 23, 59, 59, 0);

        $dt = $now->endOfDay(true);
        $this->assertTrue($dt instanceof Chronos);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 23, 59, 59, 999999);
    }

    public function testStartOfMonthIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->startOfMonth();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfMonthFromNow()
    {
        $dt = Chronos::now()->startOfMonth();
        $this->assertDateTime($dt, $dt->year, $dt->month, 1, 0, 0, 0);
    }

    public function testStartOfMonthFromLastDay()
    {
        $dt = Chronos::create(2000, 1, 31, 2, 3, 4)->startOfMonth();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->startOfYear();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfYearFromNow()
    {
        $dt = Chronos::now()->startOfYear();
        $this->assertDateTime($dt, $dt->year, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromFirstDay()
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromLastDay()
    {
        $dt = Chronos::create(2000, 12, 31, 23, 59, 59)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfMonthIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->endOfMonth();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfMonth()
    {
        $dt = Chronos::create(2000, 1, 1, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfMonthFromLastDay()
    {
        $dt = Chronos::create(2000, 1, 31, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfYearIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->endOfYear();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfYearFromNow()
    {
        $dt = Chronos::now()->endOfYear();
        $this->assertDateTime($dt, $dt->year, 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromFirstDay()
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromLastDay()
    {
        $dt = Chronos::create(2000, 12, 31, 23, 59, 59)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testStartOfDecadeIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->startOfDecade();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfDecadeFromNow()
    {
        $dt = Chronos::now()->startOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromFirstDay()
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromLastDay()
    {
        $dt = Chronos::create(2009, 12, 31, 23, 59, 59)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfDecadeIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->endOfDecade();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfDecadeFromNow()
    {
        $dt = Chronos::now()->endOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10 + 9, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromFirstDay()
    {
        $dt = Chronos::create(2000, 1, 1, 1, 1, 1)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromLastDay()
    {
        $dt = Chronos::create(2009, 12, 31, 23, 59, 59)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testStartOfCenturyIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->startOfCentury();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testStartOfCenturyFromNow()
    {
        $now = Chronos::now();
        $dt = Chronos::now()->startOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 1, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromFirstDay()
    {
        $dt = Chronos::create(2001, 1, 1, 1, 1, 1)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromLastDay()
    {
        $dt = Chronos::create(2100, 12, 31, 23, 59, 59)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    public function testEndOfCenturyIsFluid()
    {
        $now = Chronos::now();
        $dt = $now->endOfCentury();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testEndOfCenturyFromNow()
    {
        $now = Chronos::now();
        $dt = Chronos::now()->endOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 100, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromFirstDay()
    {
        $dt = Chronos::create(2001, 1, 1, 1, 1, 1)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromLastDay()
    {
        $dt = Chronos::create(2100, 12, 31, 23, 59, 59)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    public function testAverageIsFluid()
    {
        $dt = Chronos::now()->average();
        $this->assertTrue($dt instanceof Chronos);
    }

    public function testAverageFromSame()
    {
        $dt1 = Chronos::create(2000, 1, 31, 2, 3, 4);
        $dt2 = Chronos::create(2000, 1, 31, 2, 3, 4)->average($dt1);
        $this->assertDateTime($dt2, 2000, 1, 31, 2, 3, 4);
    }

    public function testAverageFromGreater()
    {
        $dt1 = Chronos::create(2000, 1, 1, 1, 1, 1);
        $dt2 = Chronos::create(2009, 12, 31, 23, 59, 59)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }

    public function testAverageFromLower()
    {
        $dt1 = Chronos::create(2009, 12, 31, 23, 59, 59);
        $dt2 = Chronos::create(2000, 1, 1, 1, 1, 1)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }
}
