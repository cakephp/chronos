<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\DateTime;

use TestCase;

class StartEndOfTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfDay($class)
    {
        $dt = $class::now();
        $dt = $dt->startOfDay();
        $this->assertTrue($dt instanceof $class);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfDay($class)
    {
        $dt = $class::now();
        $dt = $dt->endOfDay();
        $this->assertTrue($dt instanceof $class);
        $this->assertDateTime($dt, $dt->year, $dt->month, $dt->day, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfMonthIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->startOfMonth();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfMonthFromNow($class)
    {
        $dt = $class::now()->startOfMonth();
        $this->assertDateTime($dt, $dt->year, $dt->month, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfMonthFromLastDay($class)
    {
        $dt = $class::create(2000, 1, 31, 2, 3, 4)->startOfMonth();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfYearIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->startOfYear();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfYearFromNow($class)
    {
        $dt = $class::now()->startOfYear();
        $this->assertDateTime($dt, $dt->year, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfYearFromFirstDay($class)
    {
        $dt = $class::create(2000, 1, 1, 1, 1, 1)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfYearFromLastDay($class)
    {
        $dt = $class::create(2000, 12, 31, 23, 59, 59)->startOfYear();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfMonthIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->endOfMonth();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfMonth($class)
    {
        $dt = $class::create(2000, 1, 1, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfMonthFromLastDay($class)
    {
        $dt = $class::create(2000, 1, 31, 2, 3, 4)->endOfMonth();
        $this->assertDateTime($dt, 2000, 1, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfYearIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->endOfYear();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfYearFromNow($class)
    {
        $dt = $class::now()->endOfYear();
        $this->assertDateTime($dt, $dt->year, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfYearFromFirstDay($class)
    {
        $dt = $class::create(2000, 1, 1, 1, 1, 1)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfYearFromLastDay($class)
    {
        $dt = $class::create(2000, 12, 31, 23, 59, 59)->endOfYear();
        $this->assertDateTime($dt, 2000, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfDecadeIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->startOfDecade();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfDecadeFromNow($class)
    {
        $dt = $class::now()->startOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfDecadeFromFirstDay($class)
    {
        $dt = $class::create(2000, 1, 1, 1, 1, 1)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfDecadeFromLastDay($class)
    {
        $dt = $class::create(2009, 12, 31, 23, 59, 59)->startOfDecade();
        $this->assertDateTime($dt, 2000, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfDecadeIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->endOfDecade() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfDecadeFromNow($class)
    {
        $dt = $class::now()->endOfDecade();
        $this->assertDateTime($dt, $dt->year - $dt->year % 10 + 9, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfDecadeFromFirstDay($class)
    {
        $dt = $class::create(2000, 1, 1, 1, 1, 1)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfDecadeFromLastDay($class)
    {
        $dt = $class::create(2009, 12, 31, 23, 59, 59)->endOfDecade();
        $this->assertDateTime($dt, 2009, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfCenturyIsFluid($class)
    {
        $dt = $class::now();
        $this->assertTrue($dt->startOfCentury() instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfCenturyFromNow($class)
    {
        $now = $class::now();
        $dt = $class::now()->startOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 1, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfCenturyFromFirstDay($class)
    {
        $dt = $class::create(2001, 1, 1, 1, 1, 1)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testStartOfCenturyFromLastDay($class)
    {
        $dt = $class::create(2100, 12, 31, 23, 59, 59)->startOfCentury();
        $this->assertDateTime($dt, 2001, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfCenturyIsFluid($class)
    {
        $dt = $class::now();
        $dt = $dt->endOfCentury();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfCenturyFromNow($class)
    {
        $now = $class::now();
        $dt = $class::now()->endOfCentury();
        $this->assertDateTime($dt, $now->year - $now->year % 100 + 100, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfCenturyFromFirstDay($class)
    {
        $dt = $class::create(2001, 1, 1, 1, 1, 1)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEndOfCenturyFromLastDay($class)
    {
        $dt = $class::create(2100, 12, 31, 23, 59, 59)->endOfCentury();
        $this->assertDateTime($dt, 2100, 12, 31, 23, 59, 59);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testAverageIsFluid($class)
    {
        $dt = $class::now()->average();
        $this->assertTrue($dt instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testAverageFromSame($class)
    {
        $dt1 = $class::create(2000, 1, 31, 2, 3, 4);
        $dt2 = $class::create(2000, 1, 31, 2, 3, 4)->average($dt1);
        $this->assertDateTime($dt2, 2000, 1, 31, 2, 3, 4);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testAverageFromGreater($class)
    {
        $dt1 = $class::create(2000, 1, 1, 1, 1, 1);
        $dt2 = $class::create(2009, 12, 31, 23, 59, 59)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testAverageFromLower($class)
    {
        $dt1 = $class::create(2009, 12, 31, 23, 59, 59);
        $dt2 = $class::create(2000, 1, 1, 1, 1, 1)->average($dt1);
        $this->assertDateTime($dt2, 2004, 12, 31, 12, 30, 30);
    }
}
