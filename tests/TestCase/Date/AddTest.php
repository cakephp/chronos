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

use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateInterval;

class AddTest extends TestCase
{
    public function testAddFullDay()
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->add($interval);

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    public function testAddIgnoreTime()
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->add($interval);

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    public function testSubFullDay()
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->sub($interval);

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    public function testSubIgnoreTime()
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->sub($interval);

        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
    }

    public function testAddDay()
    {
        $this->assertSame(1, ChronosDate::create(1975, 5, 31)->addDays(1)->day);
        $this->assertSame(30, ChronosDate::create(1975, 5, 31)->addDays(-1)->day);
    }

    public function testAddMonth()
    {
        $this->assertSame(6, ChronosDate::create(1975, 5, 31)->addMonths(1)->month);
        $this->assertSame(4, ChronosDate::create(1975, 5, 31)->addMonths(-1)->month);
    }

    public function testAddYears()
    {
        $this->assertSame(1976, ChronosDate::create(1975, 5, 31)->addYears(1)->year);
        $this->assertSame(1974, ChronosDate::create(1975, 5, 31)->addYears(-1)->year);
    }

    public function testAddWeekdays()
    {
        $this->assertSame(2, ChronosDate::create(1975, 5, 31)->addWeekdays(1)->day);
        $this->assertSame(30, ChronosDate::create(1975, 5, 31)->addWeekdays(-1)->day);
    }
}
