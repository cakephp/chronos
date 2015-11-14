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
use DateInterval;
use TestCase;

class AddTest extends TestCase
{
    /**
     * @dataProvider dateClassProvider
     */
    public function testAddFullDay($class)
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = $class::create(2001, 1, 1);
        $new = $date->add($interval);

        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddIgnoreTime($class)
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = $class::create(2001, 1, 1);
        $new = $date->add($interval);

        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testSubFullDay($class)
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = $class::create(2001, 1, 1);
        $new = $date->sub($interval);

        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testSubIgnoreTime($class)
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = $class::create(2001, 1, 1);
        $new = $date->sub($interval);

        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddDay($class)
    {
        $this->assertEquals(1, $class::create(1975, 5, 31)->addDays(1)->day);
        $this->assertEquals(30, $class::create(1975, 5, 31)->addDays(-1)->day);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddMonth($class)
    {
        $this->assertEquals(6, $class::create(1975, 5, 31)->addMonths(1)->month);
        $this->assertEquals(4, $class::create(1975, 5, 31)->addMonths(-1)->month);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddYear($class)
    {
        $this->assertEquals(1976, $class::create(1975, 5, 31)->addYears(1)->year);
        $this->assertEquals(1974, $class::create(1975, 5, 31)->addYears(-1)->year);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddWeekdays($class)
    {
        $this->assertEquals(2, $class::create(1975, 5, 31)->addWeekdays(1)->day);
        $this->assertEquals(30, $class::create(1975, 5, 31)->addWeekdays(-1)->day);
    }
}
