<?php
declare(strict_types=1);

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
namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Closure;
use DateInterval;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private $saveTz;

    protected function setUp(): void
    {
        //save current timezone
        $this->saveTz = date_default_timezone_get();

        date_default_timezone_set('America/Toronto');
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->saveTz);
        Chronos::setTestNow(null);
        Date::setTestNow(null);
    }

    protected function assertTime($d, $hour, $minute, $second = null, $microsecond = null)
    {
        $this->assertSame($hour, $d->hour, 'Chronos->hour');
        $this->assertSame($minute, $d->minute, 'Chronos->minute');

        if ($second !== null) {
            $this->assertSame($second, $d->second, 'Chronos->second');
        }

        if ($microsecond !== null) {
            $this->assertSame($microsecond, $d->microsecond, 'Chronos->microsecond');
        }
    }

    protected function assertDateTime($d, $year, $month, $day, $hour = null, $minute = null, $second = null, $microsecond = null)
    {
        $this->assertSame($year, $d->year, 'Chronos->year');
        $this->assertSame($month, $d->month, 'Chronos->month');
        $this->assertSame($day, $d->day, 'Chronos->day');

        if ($hour !== null) {
            $this->assertSame($hour, $d->hour, 'Chronos->hour');
        }

        if ($minute !== null) {
            $this->assertSame($minute, $d->minute, 'Chronos->minute');
        }

        if ($second !== null) {
            $this->assertSame($second, $d->second, 'Chronos->second');
        }

        if ($microsecond !== null) {
            $this->assertSame($microsecond, $d->microsecond, 'Chronos->microsecond');
        }
    }

    protected function assertDateInterval(DateInterval $interval, $years = null, $months = null, $days = null, $hours = null, $minutes = null, $seconds = null, $microseconds = null)
    {
        if ($years !== null) {
            $this->assertSame($years, $interval->y, 'DateInterval->y');
        }

        if ($months !== null) {
            $this->assertSame($months, $interval->m, 'DateInterval->m');
        }

        if ($days !== null) {
            $this->assertSame($days, $interval->d, 'DateInterval->d');
        }

        if ($hours !== null) {
            $this->assertSame($hours, $interval->h, 'DateInterval->h');
        }

        if ($minutes !== null) {
            $this->assertSame($minutes, $interval->i, 'DateInterval->i');
        }

        if ($seconds !== null) {
            $this->assertSame($seconds, $interval->s, 'DateInterval->s');
        }

        if ($microseconds !== null) {
            $this->assertSame($microseconds, $interval->f, 'DateInterval->f');
        }
    }

    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        Chronos::setTestNow($dt ?? Chronos::now());
        $func();
        Chronos::setTestNow();
    }

    protected function withTimezone(string $tz, Closure $cb)
    {
        $restore = date_default_timezone_get();
        date_default_timezone_set($tz);
        try {
            $cb();
        } finally {
            date_default_timezone_set($restore);
        }
    }
}
