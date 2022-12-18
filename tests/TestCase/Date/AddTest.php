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
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateInterval;
use InvalidArgumentException;

class AddTest extends TestCase
{
    public function testAddFullDay()
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->add($interval);
        $this->assertSame('2001-01-02', $new->toDateString());
    }

    public function testAddRaiseErrorOnTime()
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->add($interval);
    }

    public function testSubFullDay()
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->sub($interval);
        $this->assertSame('2000-12-31', $new->toDateString());
    }

    public function testSubIgnoreTime()
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->sub($interval);
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

    public function testModify()
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('2 days');
        $this->assertSame('2001-01-03', $new->toDateString());
    }

    public function testModifyTimeComponentError()
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('10 seconds');
    }
}
