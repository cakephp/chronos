<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';

use Cake\Chronos\Carbon;
use Cake\Chronos\Chronos;
use Cake\Chronos\CarbonInterval;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    public static $class = Carbon::class;

    private $saveTz;

    protected function setUp()
    {
        //save current timezone
        $this->saveTz = date_default_timezone_get();

        date_default_timezone_set('America/Toronto');
    }

    protected function tearDown()
    {
        date_default_timezone_set($this->saveTz);
    }

    public function classNameProvider()
    {
        return [
            'mutable' => [Carbon::class],
            'immutable' => [Chronos::class]
        ];
    }

    protected function assertDateTime($d, $year, $month, $day, $hour = null, $minute = null, $second = null)
    {
        $this->assertSame($year, $d->year, 'Carbon->year');
        $this->assertSame($month, $d->month, 'Carbon->month');
        $this->assertSame($day, $d->day, 'Carbon->day');

        if ($hour !== null) {
            $this->assertSame($hour, $d->hour, 'Carbon->hour');
        }

        if ($minute !== null) {
            $this->assertSame($minute, $d->minute, 'Carbon->minute');
        }

        if ($second !== null) {
            $this->assertSame($second, $d->second, 'Carbon->second');
        }
    }

    protected function assertDateTimeInterval(CarbonInterval $ci, $years, $months = null, $days = null, $hours = null, $minutes = null, $seconds = null)
    {
        $this->assertSame($years, $ci->years, 'CarbonInterval->years');

        if ($months !== null) {
            $this->assertSame($months, $ci->months, 'CarbonInterval->months');
        }

        if ($days !== null) {
            $this->assertSame($days, $ci->dayz, 'CarbonInterval->dayz');
        }

        if ($hours !== null) {
            $this->assertSame($hours, $ci->hours, 'CarbonInterval->hours');
        }

        if ($minutes !== null) {
            $this->assertSame($minutes, $ci->minutes, 'CarbonInterval->minutes');
        }

        if ($seconds !== null) {
            $this->assertSame($seconds, $ci->seconds, 'CarbonInterval->seconds');
        }
    }

    protected function assertInstanceOfCarbonInterval($d)
    {
        $this->assertInstanceOf(CarbonInterval::class, $d);
    }

    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        Carbon::setTestNow(($dt === null) ? Carbon::now() : $dt);
        $func();
        Carbon::setTestNow();
    }
}
