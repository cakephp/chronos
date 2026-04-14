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

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use Closure;
use DatePeriod;
use PHPUnit\Framework\Attributes\DataProvider;

class DiffTest extends TestCase
{
    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        parent::wrapWithTestNow($func, $dt ?? Chronos::createFromDate(2012, 1, 1));
    }

    public function testDiffInYearsPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->addYears(1)));
    }

    public function testDiffInYearsNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(-1, $dt->diffInYears($dt->subYears(1), false));
    }

    public function testDiffInYearsNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->subYears(1)));
    }

    public function testDiffInYearsVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(1, ChronosDate::parse(Chronos::now())->subYears(1)->diffInYears());
        });
    }

    public function testDiffInYearsEnsureIsTruncated(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->addYears(1)->addMonths(7)));
    }

    public function testDiffInMonthsPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(13, $dt->diffInMonths($dt->addYears(1)->addMonths(1)));
    }

    public function testDiffInMonthsNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(-11, $dt->diffInMonths($dt->subYears(1)->addMonths(1), false));
    }

    public function testDiffInMonthsNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(11, $dt->diffInMonths($dt->subYears(1)->addMonths(1)));
    }

    public function testDiffInMonthsVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(12, ChronosDate::parse(Chronos::now())->subYears(1)->diffInMonths());
        });
    }

    public function testDiffInMonthsEnsureIsTruncated(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMonths($dt->addMonths(1)->addDays(16)));
    }

    public function testDiffInDaysPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(366, $dt->diffInDays($dt->addYears(1)));
    }

    public function testDiffInDaysNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(-365, $dt->diffInDays($dt->subYears(1), false));
    }

    public function testDiffInDaysNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(365, $dt->diffInDays($dt->subYears(1)));
    }

    public function testDiffInDaysVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(7, ChronosDate::parse(Chronos::now())->subWeeks(1)->diffInDays());
        });
    }

    public function testDiffInDaysFilteredPositiveWithMutated(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === 1;
        }, $dt->endOfMonth()));
    }

    public function testDiffInDaysFilteredPositiveWithSecondObject(): void
    {
        $dt1 = ChronosDate::create(2000, 1, 1);
        $dt2 = ChronosDate::create(2000, 1, 31);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2));
    }

    public function testDiffInDaysFilteredNegativeNoSignWithMutated(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt->startOfMonth()));
    }

    public function testDiffInDaysFilteredNegativeNoSignWithSecondObject(): void
    {
        $dt1 = ChronosDate::create(2000, 1, 31);
        $dt2 = ChronosDate::create(2000, 1, 1);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2));
    }

    public function testDiffInDaysFilteredNegativeWithSignWithMutated(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(-5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === 1;
        }, $dt->startOfMonth(), false));
    }

    public function testDiffInDaysFilteredNegativeWithSignWithSecondObject(): void
    {
        $dt1 = ChronosDate::create(2000, 1, 31);
        $dt2 = ChronosDate::create(2000, 1, 1);

        $this->assertSame(-5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2, false));
    }

    public function testDiffFilteredNegativeNoSignWithMutated(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $interval = Chronos::createInterval(days: 2);

        $this->assertSame(2, $dt->diffFiltered($interval, function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt->startOfMonth()));
    }

    public function testDiffFilteredNegativeNoSignWithSecondObject(): void
    {
        $dt1 = ChronosDate::create(2006, 1, 31);
        $dt2 = ChronosDate::create(2000, 1, 1);
        $interval = Chronos::createInterval(years: 1);

        $this->assertSame(7, $dt1->diffFiltered($interval, function ($date): bool {
            return $date->month === 1;
        }, $dt2));
    }

    public function testDiffFilteredNegativeWithSignWithMutated(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $interval = Chronos::createInterval(weeks: 1);

        $this->assertSame(-4, $dt->diffFiltered($interval, function ($date): bool {
            return $date->month === 12;
        }, $dt->subMonths(3), false));
    }

    public function testDiffFilteredNegativeWithSignWithSecondObject(): void
    {
        $dt1 = ChronosDate::create(2001, 1, 31);
        $dt2 = ChronosDate::create(1999, 1, 1);
        $interval = Chronos::createInterval(months: 1);

        $this->assertSame(-12, $dt1->diffFiltered($interval, function ($date): bool {
            return $date->year === 2000;
        }, $dt2, false));
    }

    public function testDiffFilteredWithOptions(): void
    {
        $dt1 = ChronosDate::create(2000, 1, 1);
        $dt2 = ChronosDate::create(2000, 1, 2);
        $interval = Chronos::createInterval(days: 1);

        $this->assertSame(1, $dt1->diffFiltered($interval, function ($dt): bool {
            return $dt->day === 1;
        }, $dt2));

        $this->assertSame(0, $dt1->diffFiltered($interval, function ($dt): bool {
            return $dt->day === 1;
        }, $dt2, options: DatePeriod::EXCLUDE_START_DATE));
    }

    public function testDiffInWeekdaysPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(21, $dt->diffInWeekdays($dt->endOfMonth()));
    }

    public function testDiffInWeekdaysNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(21, $dt->diffInWeekdays($dt->startOfMonth()));
    }

    public function testDiffInWeekdaysNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(-21, $dt->diffInWeekdays($dt->startOfMonth(), false));
    }

    public function testDiffInWeekendDaysPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->endOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->startOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 31);
        $this->assertSame(-10, $dt->diffInWeekendDays($dt->startOfMonth(), false));
    }

    public function testDiffInWeeksPositive(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->addYears(1)));
    }

    public function testDiffInWeeksNegativeWithSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(-52, $dt->diffInWeeks($dt->subYears(1), false));
    }

    public function testDiffInWeeksNegativeNoSign(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->subYears(1)));
    }

    public function testDiffInWeeksVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(1, ChronosDate::parse(Chronos::now())->subWeeks(1)->diffInWeeks());
        });
    }

    public function testDiffInWeeksEnsureIsTruncated(): void
    {
        $dt = ChronosDate::create(2000, 1, 1);
        $this->assertSame(0, $dt->diffInWeeks($dt->addWeeks(1)->subDays(1)));
    }

    public static function diffForHumansProvider(): array
    {
        $now = ChronosDate::parse('2020-01-04');

        return [
            [$now, $now->addYears(11), '11 years before'],
            [$now, $now->addYears(1), '1 year before'],
            [$now, $now->addMonths(11), '11 months before'],
            [$now, $now->addMonths(2), '2 months before'],
            [$now, $now->addWeeks(8), '8 weeks before'],
            [$now, $now->addDays(21), '3 weeks before'],
            [$now, $now->addDays(20), '20 days before'],
            [$now, $now->addDays(8), '8 days before'],
            [$now, $now->addDays(1), '1 day before'],
            [$now, $now->addDays(6), '6 days before'],

            [$now, $now->subYears(11), '11 years after'],
            [$now, $now->subYears(1), '1 year after'],
            [$now, $now->subMonths(11), '11 months after'],
            [$now, $now->subMonths(2), '2 months after'],
            [$now, $now->subWeeks(8), '8 weeks after'],
            [$now, $now->subDays(21), '3 weeks after'],
            [$now, $now->subDays(20), '20 days after'],
            [$now, $now->subDays(8), '8 days after'],
            [$now, $now->subDays(1), '1 day after'],
            [$now, $now->subDays(6), '6 days after'],
        ];
    }

    /**
     * @return void
     */
    #[DataProvider('diffForHumansProvider')]
    public function testDiffForHumansRelative(ChronosDate $now, ChronosDate $date, string $expected): void
    {
        $this->assertSame($expected, $now->diffForHumans($date));
    }

    public function testDiffForHumansWithNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('1 day ago', ChronosDate::parse(Chronos::now())->subDays(1)->diffForHumans());
            $this->assertSame('1 day from now', ChronosDate::parse(Chronos::now())->addDays(1)->diffForHumans());
        });
    }

    public function testDiffForHumansWithNowAbsolute(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('1 day', ChronosDate::parse(Chronos::now())->subDays(1)->diffForHumans(null, true));
            $this->assertSame('1 day', ChronosDate::parse(Chronos::now())->addDays(1)->diffForHumans(null, true));
        });
    }

    public function testDiffForHumansWithoutDiff(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('0 seconds ago', ChronosDate::parse(Chronos::now())->diffForHumans());
        });
    }

    public function testDiffForHumansWithoutDiffAbsolute(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('0 seconds', ChronosDate::parse(Chronos::now())->diffForHumans(null, true));
        });
    }
}
