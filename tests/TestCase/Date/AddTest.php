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
    public function testAddFullDay(): void
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->add($interval);
        $this->assertSame('2001-01-02', $new->toDateString());
    }

    public function testAddRaiseErrorOnTime(): void
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->add($interval);
    }

    public function testAddRaiseErrorOnNegativeTime(): void
    {
        $interval = DateInterval::createFromDateString('-1 hour');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->add($interval);
    }

    public function testSubFullDay(): void
    {
        $interval = DateInterval::createFromDateString('1 day');
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->sub($interval);
        $this->assertSame('2000-12-31', $new->toDateString());
    }

    public function testSubIgnoreTime(): void
    {
        $interval = DateInterval::createFromDateString('1 hour, 1 minute, 3 seconds');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->sub($interval);
    }

    public function testSubRaiseErrorOnNegativeTime(): void
    {
        $interval = DateInterval::createFromDateString('-1 hour');
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->sub($interval);
    }

    public function testAddDay(): void
    {
        $this->assertSame(1, ChronosDate::create(1975, 5, 31)->addDays(1)->day);
        $this->assertSame(30, ChronosDate::create(1975, 5, 31)->addDays(-1)->day);
    }

    public function testAddMonth(): void
    {
        $this->assertSame(6, ChronosDate::create(1975, 5, 31)->addMonths(1)->month);
        $this->assertSame(4, ChronosDate::create(1975, 5, 31)->addMonths(-1)->month);
    }

    public function testAddYears(): void
    {
        $this->assertSame(1976, ChronosDate::create(1975, 5, 31)->addYears(1)->year);
        $this->assertSame(1974, ChronosDate::create(1975, 5, 31)->addYears(-1)->year);
    }

    public function testAddWeekdays(): void
    {
        $this->assertSame(2, ChronosDate::create(1975, 5, 31)->addWeekdays(1)->day);
        $this->assertSame(30, ChronosDate::create(1975, 5, 31)->addWeekdays(-1)->day);
    }

    public function testModify(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('2 days');
        $this->assertSame('2001-01-03', $new->toDateString());
    }

    public function testModifyTimeComponentError(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('10 seconds');
    }

    public function testModifyOrdinalWeekday(): void
    {
        $date = ChronosDate::create(2026, 9, 1);
        $new = $date->modify('second monday of this month');
        $this->assertSame('2026-09-14', $new->toDateString());
    }

    public function testModifyUppercaseTimeComponentError(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('10 SECONDS');
    }

    public function testModifyClockTimeIgnored(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('12:00');
        $this->assertSame('2001-01-01', $new->toDateString());
    }

    public function testModifyTimeComponentWrappingDayError(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('+24 hours');
    }

    public function testModifyFractionalTimeComponentError(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('1 millisecond');
    }

    public function testModifyAbbreviatedFractionalTimeComponentError(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $this->expectException(InvalidArgumentException::class);
        $date->modify('-1 msec');
    }

    public function testModifyTimestamp(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('@86400');
        $this->assertSame('1970-01-02', $new->toDateString());
    }

    public function testModifyTimestampWithTimezone(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('@1 UTC');
        $this->assertSame('1970-01-01', $new->toDateString());
    }

    public function testModifyTimestampWithDateComponent(): void
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->modify('@86400 +1 day');
        $this->assertSame('1970-01-03', $new->toDateString());
    }

    public function testModifyAcrossSkippedMidnight(): void
    {
        $this->withTimezone('America/Havana', function (): void {
            $date = new ChronosDate('2023-03-11');
            $new = $date->modify('+1 day');
            $this->assertSame('2023-03-12', $new->toDateString());
        });
    }
}
