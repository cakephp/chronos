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
namespace Cake\Chronos\Test\Date;

use Cake\Chronos\Date;
use TestCase;

class AddTest extends TestCase
{
    public function testAddDay()
    {
        $this->assertEquals(1, Date::create(1975, 5, 31)->addDays(1)->day);
        $this->assertEquals(30, Date::create(1975, 5, 31)->addDays(-1)->day);
    }

    public function testAddMonth()
    {
        $this->assertEquals(6, Date::create(1975, 5, 31)->addMonths(1)->month);
        $this->assertEquals(4, Date::create(1975, 5, 31)->addMonths(-1)->month);
    }

    public function testAddYear()
    {
        $this->assertEquals(1976, Date::create(1975, 5, 31)->addYears(1)->year);
        $this->assertEquals(1974, Date::create(1975, 5, 31)->addYears(-1)->year);
    }

    public function testAddWeekdays()
    {
        $this->assertEquals(2, Date::create(1975, 5, 31)->addWeekdays(1)->day);
        $this->assertEquals(30, Date::create(1975, 5, 31)->addWeekdays(-1)->day);
    }
}
