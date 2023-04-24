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

use Cake\Chronos\MutableDateTime;
use Cake\Chronos\Test\TestCase\TestCase;

class CopyTest extends TestCase
{
    public function testCopy()
    {
        $dating = MutableDateTime::now();
        $dating2 = $dating->copy();
        $this->assertNotSame($dating, $dating2);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCopyEnsureTzIsCopied($class)
    {
        $dating = $class::createFromDate(2000, 1, 1, 'Europe/London');
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
        $micro = 254687;
        $dating = $class::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.' . $micro);
        $dating2 = $dating->copy();
        $this->assertSame($micro, $dating2->micro);
    }
}
