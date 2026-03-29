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
namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\DifferenceFormatter;
use Cake\Chronos\Test\TestCase\TestCase;
use Closure;
use DatePeriod;
use DateTimeZone;
use PHPUnit\Framework\Attributes\DataProvider;

class DiffTest extends TestCase
{
    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        parent::wrapWithTestNow($func, $dt ?? Chronos::createFromDate(2012, 1, 1));
    }

    public function testDiffInYearsPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->addYears(1)));
    }

    public function testDiffInYearsNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-1, $dt->diffInYears($dt->subYears(1), false));
    }

    public function testDiffInYearsNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->subYears(1)));
    }

    public function testDiffInYearsVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(1, Chronos::now()->subYears(1)->diffInYears());
        });
    }

    public function testDiffInYearsEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->addYears(1)->addMonths(7)));
    }

    public function testDiffInMonthsPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(13, $dt->diffInMonths($dt->addYears(1)->addMonths(1)));
    }

    public function testDiffInMonthsNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-11, $dt->diffInMonths($dt->subYears(1)->addMonths(1), false));
    }

    public function testDiffInMonthsNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(11, $dt->diffInMonths($dt->subYears(1)->addMonths(1)));
    }

    public function testDiffInMonthsVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(12, Chronos::now()->subYears(1)->diffInMonths());
        });
    }

    public function testDiffInMonthsEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMonths($dt->addMonths(1)->addDays(16)));
    }

    public function testDiffInMonthsIgnoreTimezone(): void
    {
        $tokyoStart = new Chronos('2019-06-01', new DateTimeZone('Asia/Tokyo'));
        $utcStart = new Chronos('2019-06-01', new DateTimeZone('UTC'));
        foreach (range(1, 6) as $monthOffset) {
            $end = new Chronos(sprintf('2019-%02d-01', 6 + $monthOffset), new DateTimeZone('Asia/Tokyo'));
            $this->assertSame($monthOffset, $tokyoStart->diffInMonthsIgnoreTimezone($end));
            $this->assertSame($monthOffset, $utcStart->diffInMonthsIgnoreTimezone($end));

            $end = new Chronos(sprintf('2020-%02d-01', 6 + $monthOffset), new DateTimeZone('Asia/Tokyo'));
            $this->assertSame($monthOffset + 12, $tokyoStart->diffInMonthsIgnoreTimezone($end));
            $this->assertSame($monthOffset + 12, $utcStart->diffInMonthsIgnoreTimezone($end));
        }

        $this->wrapWithTestNow(function (): void {
            $this->assertSame(1, Chronos::now()->subMonths(1)->startOfMonth()->diffInMonthsIgnoreTimezone());
        });
    }

    public function testDiffInDaysPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(366, $dt->diffInDays($dt->addYears(1)));
    }

    public function testDiffInDaysNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-365, $dt->diffInDays($dt->subYears(1), false));
    }

    public function testDiffInDaysNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(365, $dt->diffInDays($dt->subYears(1)));
    }

    public function testDiffInDaysVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(7, Chronos::now()->subWeeks(1)->diffInDays());
        });
    }

    public function testDiffInDaysEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInDays($dt->addDays(1)->addHours(13)));
    }

    public function testDiffInDaysFilteredPositiveWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === 1;
        }, $dt->endOfMonth()));
    }

    public function testDiffInDaysFilteredPositiveWithSecondObject(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 1);
        $dt2 = Chronos::createFromDate(2000, 1, 31);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2));
    }

    public function testDiffInDaysFilteredNegativeNoSignWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt->startOfMonth()));
    }

    public function testDiffInDaysFilteredNegativeNoSignWithSecondObject(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 31);
        $dt2 = Chronos::createFromDate(2000, 1, 1);

        $this->assertSame(5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2));
    }

    public function testDiffInDaysFilteredNegativeWithSignWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(-5, $dt->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === 1;
        }, $dt->startOfMonth(), false));
    }

    public function testDiffInDaysFilteredNegativeWithSignWithSecondObject(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 31);
        $dt2 = Chronos::createFromDate(2000, 1, 1);

        $this->assertSame(-5, $dt1->diffInDaysFiltered(function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt2, false));
    }

    public function testDiffInHoursFiltered(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = Chronos::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(31, $dt1->diffInHoursFiltered(function ($date): bool {
            return $date->hour === 9;
        }, $dt2));
    }

    public function testDiffInHoursFilteredNegative(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = Chronos::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(-31, $dt1->diffInHoursFiltered(function ($date): bool {
            return $date->hour === 9;
        }, $dt2, false));
    }

    public function testDiffInHoursFilteredWorkHoursPerWeek(): void
    {
        $dt1 = Chronos::createFromDate(2000, 1, 5)->endOfDay();
        $dt2 = Chronos::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(40, $dt1->diffInHoursFiltered(function ($date): bool {
            return $date->hour > 8 && $date->hour < 17;
        }, $dt2));
    }

    public function testDiffFilteredUsingMinutesPositiveWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1)->startOfDay();
        $interval = Chronos::createInterval(minutes: 1);
        $this->assertSame(60, $dt->diffFiltered($interval, function ($date): bool {
            return $date->hour === 12;
        }, Chronos::createFromDate(2000, 1, 1)->endOfDay()));
    }

    public function testDiffFilteredPositiveWithSecondObject(): void
    {
        $dt1 = Chronos::create(2000, 1, 1);
        $dt2 = $dt1->addSeconds(80);
        $interval = Chronos::createInterval(seconds: 1);

        $this->assertSame(40, $dt1->diffFiltered($interval, function ($date): bool {
            return $date->second % 2 === 0;
        }, $dt2));
    }

    public function testDiffFilteredNegativeNoSignWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $interval = Chronos::createInterval(days: 2);

        $this->assertSame(2, $dt->diffFiltered($interval, function ($date): bool {
            return $date->dayOfWeek === Chronos::SUNDAY;
        }, $dt->startOfMonth()));
    }

    public function testDiffFilteredNegativeNoSignWithSecondObject(): void
    {
        $dt1 = Chronos::createFromDate(2006, 1, 31);
        $dt2 = Chronos::createFromDate(2000, 1, 1);
        $interval = Chronos::createInterval(years: 1);

        $this->assertSame(7, $dt1->diffFiltered($interval, function ($date): bool {
            return $date->month === 1;
        }, $dt2));
    }

    public function testDiffFilteredNegativeWithSignWithMutated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $interval = Chronos::createInterval(weeks: 1);

        $this->assertSame(-4, $dt->diffFiltered($interval, function ($date): bool {
            return $date->month === 12;
        }, $dt->subMonths(3), false));
    }

    public function testDiffFilteredNegativeWithSignWithSecondObject(): void
    {
        $dt1 = Chronos::createFromDate(2001, 1, 31);
        $dt2 = Chronos::createFromDate(1999, 1, 1);
        $interval = Chronos::createInterval(months: 1);

        $this->assertSame(-12, $dt1->diffFiltered($interval, function ($date): bool {
            return $date->year === 2000;
        }, $dt2, false));
    }

    public function testDiffFilteredWithOptions(): void
    {
        $dt1 = Chronos::create(2000, 1, 1);
        $dt2 = Chronos::create(2000, 1, 2);
        $interval = Chronos::createInterval(days: 1);

        $this->assertSame(1, $dt1->diffFiltered($interval, function ($dt): bool {
            return $dt->day === 1;
        }, $dt2));

        $this->assertSame(0, $dt1->diffFiltered($interval, function ($dt): bool {
            return $dt->day === 1;
        }, $dt2, options: DatePeriod::EXCLUDE_START_DATE));
    }

    public function testBug188DiffWithSameDates(): void
    {
        $start = Chronos::create(2014, 10, 8, 15, 20, 0);
        $end = clone $start;

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithDatesOnlyHoursApart(): void
    {
        $start = Chronos::create(2014, 10, 8, 15, 20, 0);
        $end = clone $start;

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithSameDates1DayApart(): void
    {
        $start = Chronos::create(2014, 10, 8, 15, 20, 0);
        $end = $start->addDays(1);

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(1, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithDatesOnTheWeekend(): void
    {
        $start = Chronos::create(2014, 1, 1, 0, 0, 0);
        $start = $start->next(Chronos::SATURDAY);

        $end = $start->addDays(1);

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testDiffInWeekdaysPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(21, $dt->diffInWeekdays($dt->endOfMonth()));
    }

    public function testDiffInWeekdaysNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(21, $dt->diffInWeekdays($dt->startOfMonth()));
    }

    public function testDiffInWeekdaysNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(-21, $dt->diffInWeekdays($dt->startOfMonth(), false));
    }

    public function testDiffInWeekendDaysPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->endOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->startOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 31);
        $this->assertSame(-10, $dt->diffInWeekendDays($dt->startOfMonth(), false));
    }

    public function testDiffInWeeksPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->addYears(1)));
    }

    public function testDiffInWeeksNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-52, $dt->diffInWeeks($dt->subYears(1), false));
    }

    public function testDiffInWeeksNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->subYears(1)));
    }

    public function testDiffInWeeksVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(1, Chronos::now()->subWeeks(1)->diffInWeeks());
        });
    }

    public function testDiffInWeeksEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(0, $dt->diffInWeeks($dt->addWeeks(1)->subDays(1)));
    }

    public function testDiffInHoursPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(26, $dt->diffInHours($dt->addDays(1)->addHours(2)));
    }

    public function testDiffInHoursNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-22, $dt->diffInHours($dt->subDays(1)->addHours(2), false));
    }

    public function testDiffInHoursNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(22, $dt->diffInHours($dt->subDays(1)->addHours(2)));
    }

    public function testDiffInHoursVsDefaultNow(): void
    {
        date_default_timezone_set('UTC');
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(48, Chronos::now()->subDays(2)->diffInHours());
        }, Chronos::create(2012, 1, 15));
    }

    public function testDiffInHoursEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInHours($dt->addHours(1)->addMinutes(31)));
    }

    public function testDiffInMinutesPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInMinutes($dt->addHours(1)->addMinutes(2)));
    }

    public function testDiffInMinutesPositiveAlot(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1502, $dt->diffInMinutes($dt->addHours(25)->addMinutes(2)));
    }

    public function testDiffInMinutesNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInMinutes($dt->subHours(1)->addMinutes(2), false));
    }

    public function testDiffInMinutesNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInMinutes($dt->subHours(1)->addMinutes(2)));
    }

    public function testDiffInMinutesVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(60, Chronos::now()->subHours(1)->diffInMinutes());
        });
    }

    public function testDiffInMinutesEnsureIsTruncated(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMinutes($dt->addMinutes(1)->addSeconds(31)));
    }

    public function testDiffInSecondsPositive(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInSeconds($dt->addMinutes(1)->addSeconds(2)));
    }

    public function testDiffInSecondsPositiveAlot(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(7202, $dt->diffInSeconds($dt->addHours(2)->addSeconds(2)));
    }

    public function testDiffInSecondsNegativeWithSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInSeconds($dt->subMinutes(1)->addSeconds(2), false));
    }

    public function testDiffInSecondsNegativeNoSign(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInSeconds($dt->subMinutes(1)->addSeconds(2)));
    }

    public function testDiffInSecondsVsDefaultNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame(3600, Chronos::now()->subHours(1)->diffInSeconds());
        });
    }

    public function testDiffInSecondsWithTimezones(): void
    {
        $dtOttawa = Chronos::createFromDate(2000, 1, 1, 'America/Toronto');
        $dtVancouver = Chronos::createFromDate(2000, 1, 1, 'America/Vancouver');
        $this->assertSame(3 * 60 * 60, $dtOttawa->diffInSeconds($dtVancouver));
    }

    public function testDiffInSecondsWithTimezonesAndVsDefaulessThan(): void
    {
        $vanNow = Chronos::now('America/Vancouver');
        $hereNow = $vanNow->setTimezone(Chronos::now()->tz);

        $this->wrapWithTestNow(function () use ($vanNow): void {
            $this->assertSame(0, $vanNow->diffInSeconds());
        }, $hereNow);
    }

    /**
     * Tests the "from now" time calculation.
     */
    public function testFromNow(): void
    {
        $date = Chronos::now();
        $date = $date->modify('-1 year')
            ->modify('-6 days')
            ->modify('-51 seconds');
        $interval = Chronos::fromNow($date);
        $result = $interval->format('%y %m %d %H %i %s');
        $this->assertSame($result, '1 0 6 00 0 51');
    }

    public static function diffForHumansProvider(): array
    {
        $now = Chronos::parse('2020-01-04 10:01:01');

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
            [$now, $now->addHours(1), '1 hour before'],
            [$now, $now->addHours(5), '5 hours before'],
            [$now, $now->addHours(23), '23 hours before'],
            [$now, $now->addMinutes(1), '1 minute before'],
            [$now, $now->addMinutes(5), '5 minutes before'],
            [$now, $now->addMinutes(59), '59 minutes before'],
            [$now, $now->addSeconds(1), '1 second before'],
            [$now, $now->addSeconds(5), '5 seconds before'],
            [$now, $now->addSeconds(59), '59 seconds before'],

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
            [$now, $now->subHours(1), '1 hour after'],
            [$now, $now->subHours(5), '5 hours after'],
            [$now, $now->subHours(23), '23 hours after'],
            [$now, $now->subMinutes(1), '1 minute after'],
            [$now, $now->subMinutes(5), '5 minutes after'],
            [$now, $now->subMinutes(59), '59 minutes after'],
            [$now, $now->subSeconds(1), '1 second after'],
            [$now, $now->subSeconds(5), '5 seconds after'],
            [$now, $now->subSeconds(59), '59 seconds after'],
        ];
    }

    /**
     * @return void
     */
    #[DataProvider('diffForHumansProvider')]
    public function testDiffForHumansRelative(Chronos $now, Chronos $date, string $expected): void
    {
        $this->assertSame($expected, $now->diffForHumans($date));
    }

    public function testDiffForHumansWithNow(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('1 second ago', Chronos::now()->subSeconds(1)->diffForHumans());
            $this->assertSame('1 second from now', Chronos::now()->addSeconds(1)->diffForHumans());
        });
    }

    public function testDiffForHumansWithNowAbsolute(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('1 second', Chronos::now()->subSeconds(1)->diffForHumans(null, true));
            $this->assertSame('1 second', Chronos::now()->addSeconds(1)->diffForHumans(null, true));
        });
    }

    public function testDiffForHumansWithoutDiff(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('0 seconds ago', Chronos::now()->diffForHumans());
        });
    }

    public function testDiffForHumansWithoutDiffAbsolute(): void
    {
        $this->wrapWithTestNow(function (): void {
            $this->assertSame('0 seconds', Chronos::now()->diffForHumans(null, true));
        });
    }

    public function testDiffFormatterSameObject(): void
    {
        $formatter = Chronos::diffFormatter();
        $this->assertInstanceOf('Cake\Chronos\DifferenceFormatter', $formatter);

        $second = Chronos::diffFormatter();
        $this->assertSame($second, $formatter, 'Same object returned on multiple calls');
    }

    public function testDiffFormatterSetter(): void
    {
        $formatter = new DifferenceFormatter();
        $result = Chronos::diffFormatter($formatter);
        $this->assertSame($result, $formatter, 'Should return parameter');

        $second = Chronos::diffFormatter();
        $this->assertSame($second, $formatter, 'Same object returned later');
    }
}
