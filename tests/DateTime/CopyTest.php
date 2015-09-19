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
use TestCase;

class CopyTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCopy($class)
    {
        $dating  = $class::now();
        $dating2 = $dating->copy();
        $this->assertNotSame($dating, $dating2);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCopyEnsureTzIsCopied($class)
    {
        $dating  = $class::createFromDate(2000, 1, 1, 'Europe/London');
        $dating2 = $dating->copy();
        $this->assertSame($dating->tzName, $dating2->tzName);
        $this->assertSame($dating->offset, $dating2->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCopyEnsureMicrosAreCopied($class)
    {
        $micro   = 254687;
        $dating  = $class::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.' . $micro);
        $dating2 = $dating->copy();
        $this->assertSame($micro, $dating2->micro);
    }
}
