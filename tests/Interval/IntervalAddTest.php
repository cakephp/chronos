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

namespace Cake\Chronos\Test\Interval;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterval;
use DateInterval;
use TestCase;

class IntervalAddTest extends TestCase
{

    public function testAdd()
    {
        $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add(new DateInterval('P2Y1M5DT22H33M44S'));
        $this->assertDateTimeInterval($ci, 6, 4, 54, 30, 43, 55);
    }

    public function testAddWithDiffDateInterval()
    {
        $diff = Chronos::now()->diff(Chronos::now()->addWeeks(3));
        $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertDateTimeInterval($ci, 4, 3, 70, 8, 10, 11);
    }

    public function testAddWithNegativeDiffDateInterval()
    {
        $diff = Chronos::now()->diff(Chronos::now()->subWeeks(3));
        $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertDateTimeInterval($ci, 4, 3, 28, 8, 10, 11);
    }
}
