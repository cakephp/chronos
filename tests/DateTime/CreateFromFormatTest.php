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

class CreateFromFormatTest extends TestFixture
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatReturnsCarbon($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertCarbon($d, 1975, 5, 21, 22, 32, 11);
        $this->assertTrue($d instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithTimezoneString($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', 'Europe/London');
        $this->assertCarbon($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithTimezone($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', new \DateTimeZone('Europe/London'));
        $this->assertCarbon($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithMillis($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s.u', '1975-05-21 22:32:11.254687');
        $this->assertSame(254687, $d->micro);
    }
}
