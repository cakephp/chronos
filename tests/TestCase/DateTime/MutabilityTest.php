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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\MutableDateTime;
use Cake\Chronos\Test\TestCase\TestCase;

class MutabilityTest extends TestCase
{
    public function testAdd()
    {
        $dt1 = MutableDateTime::createFromDate(2000, 1, 1);
        $dt2 = $dt1->addDay();
        $this->assertSame($dt1, $dt2);
    }

    public function testSet()
    {
        $dt1 = MutableDateTime::createFromDate(2000, 1, 1);
        $dt2 = $dt1->setDateTime(2001, 2, 2, 10, 20, 30);
        $this->assertSame($dt1, $dt2);
    }
}
