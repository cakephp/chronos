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

class CreateFromTimestampTest extends TestFixture
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateReturnsDatingInstance($class)
    {
        $d = $class::createFromTimestamp(Carbon::create(1975, 5, 21, 22, 32, 5)->timestamp);
        $this->assertCarbon($d, 1975, 5, 21, 22, 32, 5);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampUsesDefaultTimezone($class)
    {
        $d = $class::createFromTimestamp(0);

        // We know Toronto is -5 since no DST in Jan
        $this->assertSame(1969, $d->year);
        $this->assertSame(-5 * 3600, $d->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampWithDateTimeZone($class)
    {
        $d = $class::createFromTimestamp(0, new \DateTimeZone('UTC'));
        $this->assertSame('UTC', $d->tzName);
        $this->assertCarbon($d, 1970, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampWithString($class)
    {
        $d = $class::createFromTimestamp(0, 'UTC');
        $this->assertCarbon($d, 1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->offset);
        $this->assertSame('UTC', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampGMTDoesNotUseDefaultTimezone($class)
    {
        $d = $class::createFromTimestampUTC(0);
        $this->assertCarbon($d, 1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->offset);
    }
}
