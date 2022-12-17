<?php
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

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\MutableDateTime;
use TestCase;

class MutabilityTest extends TestCase
{

    public function testAdd()
    {
        $dt1 = MutableDateTime::createFromDate(2000, 1, 1);
        $dt2 = $dt1->addDay();
        $this->assertEquals($dt1, $dt2);
    }

    public function testSet()
    {
        $dt1 = MutableDateTime::createFromDate(2000, 1, 1);
        $dt2 = $dt1->setDateTime(2001, 2, 2, 10, 20, 30);
        $this->assertEquals($dt1, $dt2);
    }
}
