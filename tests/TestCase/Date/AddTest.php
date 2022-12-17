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
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateInterval;

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

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddIgnoreTime($class)
    {
        $scenario = function () use ($class) {
            $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
            $date = $class::create(2001, 1, 1);
            $new = $date->add($interval);

            $this->assertSame(0, $new->hour);
            $this->assertSame(0, $new->minute);
            $this->assertSame(0, $new->second);
            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);
        };
        if ($class == MutableDate::class) {
            return $scenario();
        }
        $this->deprecated($scenario);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testSubFullDay($class)
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = $class::create(2001, 1, 1);
        $new = $date->sub($interval);

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testSubIgnoreTime($class)
    {
        $scenario = function () use ($class) {
            $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
            $date = $class::create(2001, 1, 1);
            $new = $date->sub($interval);

            $this->assertSame(0, $new->hour);
            $this->assertSame(0, $new->minute);
            $this->assertSame(0, $new->second);
            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);
        };
        if ($class == MutableDate::class) {
            return $scenario();
        }
        $this->deprecated($scenario);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddDay($class)
    {
        $this->assertSame(1, $class::create(1975, 5, 31)->addDays(1)->day);
        $this->assertSame(30, $class::create(1975, 5, 31)->addDays(-1)->day);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddMonth($class)
    {
        $this->assertSame(6, $class::create(1975, 5, 31)->addMonths(1)->month);
        $this->assertSame(4, $class::create(1975, 5, 31)->addMonths(-1)->month);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddYear($class)
    {
        $this->assertSame(1976, $class::create(1975, 5, 31)->addYears(1)->year);
        $this->assertSame(1974, $class::create(1975, 5, 31)->addYears(-1)->year);
    }

    /**
     * @dataProvider dateClassProvider
     */
    public function testAddWeekdays($class)
    {
        $this->assertSame(2, $class::create(1975, 5, 31)->addWeekdays(1)->day);
        $this->assertSame(30, $class::create(1975, 5, 31)->addWeekdays(-1)->day);
    }
}
