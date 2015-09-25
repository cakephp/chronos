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

use Cake\Chronos\Chronos;
use Cake\Chronos\MutableDateTime;
use TestCase;

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
