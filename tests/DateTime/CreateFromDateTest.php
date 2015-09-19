<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Carbon;
use TestFixture;

class CreateFromDateTest extends TestFixture
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDefaults($class)
    {
        $d = $class::createFromDate();
        $this->assertSame($d->timestamp, $class::create(null, null, null, null, null, null)->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDate($class)
    {
        $d = $class::createFromDate(1975, 5, 21);
        $this->assertCarbon($d, 1975, 5, 21);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithYear($class)
    {
        $d = $class::createFromDate(1975);
        $this->assertSame(1975, $d->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithMonth($class)
    {
        $d = $class::createFromDate(null, 5);
        $this->assertSame(5, $d->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDay($class)
    {
        $d = $class::createFromDate(null, null, 21);
        $this->assertSame(21, $d->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithTimezone($class)
    {
        $d = $class::createFromDate(1975, 5, 21, 'Europe/London');
        $this->assertCarbon($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDateTimeZone($class)
    {
        $d = $class::createFromDate(1975, 5, 21, new \DateTimeZone('Europe/London'));
        $this->assertCarbon($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
