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
use Cake\Chronos\Test\TestCase\TestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestWith;

class GettersTest extends TestCase
{
    public function testGettersThrowExceptionOnUnknownGetter(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Chronos::create(1234, 5, 6, 7, 8, 9)->sdfsdfss;
    }

    public function testYearGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(1234, $d->year);
    }

    public function testYearIsoGetter(): void
    {
        $d = Chronos::createFromDate(2012, 12, 31);
        $this->assertSame(2013, $d->yearIso);
    }

    public function testMonthGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(5, $d->month);
    }

    public function testDayGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(6, $d->day);
    }

    public function testHourGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(7, $d->hour);
    }

    public function testMinuteGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(8, $d->minute);
    }

    public function testSecondGetter(): void
    {
        $d = Chronos::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(9, $d->second);
    }

    public function testMicroGetter(): void
    {
        $micro = 345678;
        $d = Chronos::parse('2014-01-05 12:34:11.' . $micro);
        $this->assertSame($micro, $d->micro);
    }

    public function testDayOfWeekGetter(): void
    {
        $d = Chronos::create(2012, 5, 7, 7, 8, 9);
        $this->assertSame(Chronos::MONDAY, $d->dayOfWeek);
    }

    public function testDayOfWeekNameGetter(): void
    {
        $d = Chronos::create(2012, 5, 7, 7, 8, 9);
        $this->assertSame('Monday', $d->dayOfWeekName);
    }

    public function testDayOfYearGetter(): void
    {
        $d = Chronos::createFromDate(2012, 5, 7);
        $this->assertSame(127, $d->dayOfYear);
    }

    public function testDaysInMonthGetter(): void
    {
        $d = Chronos::createFromDate(2012, 5, 7);
        $this->assertSame(31, $d->daysInMonth);
    }

    public function testTimestampGetter(): void
    {
        $d = Chronos::create();
        $d = $d->setTimezone('GMT')->setDateTime(1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->timestamp);
    }

    public function testGetAge(): void
    {
        $d = Chronos::now();
        $this->assertSame(0, $d->age);
    }

    public function testGetAgeWithRealAge(): void
    {
        $d = Chronos::createFromDate(1975, 5, 21);
        $age = intval(substr(
            (string)(date('Ymd') - date('Ymd', $d->timestamp)),
            0,
            -4,
        ));

        $this->assertSame($age, $d->age);
    }

    public function testGetQuarterFirst(): void
    {
        $d = Chronos::createFromDate(2012, 1, 1);
        $this->assertSame(1, $d->quarter);
    }

    public function testGetQuarterFirstEnd(): void
    {
        $d = Chronos::createFromDate(2012, 3, 31);
        $this->assertSame(1, $d->quarter);
    }

    public function testGetQuarterSecond(): void
    {
        $d = Chronos::createFromDate(2012, 4, 1);
        $this->assertSame(2, $d->quarter);
    }

    public function testGetQuarterThird(): void
    {
        $d = Chronos::createFromDate(2012, 7, 1);
        $this->assertSame(3, $d->quarter);
    }

    public function testGetQuarterFourth(): void
    {
        $d = Chronos::createFromDate(2012, 10, 1);
        $this->assertSame(4, $d->quarter);
    }

    public function testGetQuarterFirstLast(): void
    {
        $d = Chronos::createFromDate(2012, 12, 31);
        $this->assertSame(4, $d->quarter);
    }

    #[TestWith([1, 1])]
    #[TestWith([2, 1])]
    #[TestWith([3, 1])]
    #[TestWith([4, 1])]
    #[TestWith([5, 1])]
    #[TestWith([6, 1])]
    #[TestWith([7, 2])]
    #[TestWith([8, 2])]
    #[TestWith([9, 2])]
    #[TestWith([10, 2])]
    #[TestWith([11, 2])]
    #[TestWith([12, 2])]
    public function testHalfOfYear(int $month, int $expectedHalfOfYear): void
    {
        $d = Chronos::createFromDate(2012, $month, 1);
        $this->assertSame($expectedHalfOfYear, $d->half);
    }

    public function testGetLocalTrue(): void
    {
        // Default timezone has been set to America/Toronto in TestCase.php
        // @see : https://en.wikipedia.org/wiki/List_of_UTC_time_offsets
        $this->assertTrue(Chronos::createFromDate(2012, 1, 1, 'America/Toronto')->local);
        $this->assertTrue(Chronos::createFromDate(2012, 1, 1, 'America/New_York')->local);
    }

    public function testGetLocalFalse(): void
    {
        $this->assertFalse(Chronos::createFromDate(2012, 7, 1, 'UTC')->local);
        $this->assertFalse(Chronos::createFromDate(2012, 7, 1, 'Europe/London')->local);
    }

    public function testGetUtcFalse(): void
    {
        $this->assertFalse(Chronos::createFromDate(2013, 1, 1, 'America/Toronto')->utc);
        $this->assertFalse(Chronos::createFromDate(2013, 1, 1, 'Europe/Paris')->utc);
    }

    public function testGetUtcTrue(): void
    {
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Atlantic/Reykjavik')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Europe/Lisbon')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Africa/Casablanca')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Africa/Dakar')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Europe/Dublin')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'Europe/London')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'UTC')->utc);
        $this->assertTrue(Chronos::createFromDate(2013, 1, 1, 'GMT')->utc);
    }

    public function testGetDstFalse(): void
    {
        $this->assertFalse(Chronos::createFromDate(2012, 1, 1, 'America/Toronto')->dst);
    }

    public function testGetDstTrue(): void
    {
        $this->assertTrue(Chronos::createFromDate(2012, 7, 1, 'America/Toronto')->dst);
    }

    public function testOffsetForTorontoWithDST(): void
    {
        $this->assertSame(-18000, Chronos::createFromDate(2012, 1, 1, 'America/Toronto')->offset);
    }

    public function testOffsetForTorontoNoDST(): void
    {
        $this->assertSame(-14400, Chronos::createFromDate(2012, 6, 1, 'America/Toronto')->offset);
    }

    public function testOffsetForGMT(): void
    {
        $this->assertSame(0, Chronos::createFromDate(2012, 6, 1, 'GMT')->offset);
    }

    public function testOffsetHoursForTorontoWithDST(): void
    {
        $this->assertSame(-5, Chronos::createFromDate(2012, 1, 1, 'America/Toronto')->offsetHours);
    }

    public function testOffsetHoursForTorontoNoDST(): void
    {
        $this->assertSame(-4, Chronos::createFromDate(2012, 6, 1, 'America/Toronto')->offsetHours);
    }

    public function testOffsetHoursForGMT(): void
    {
        $this->assertSame(0, Chronos::createFromDate(2012, 6, 1, 'GMT')->offsetHours);
    }

    public function testIsLeapYearTrue(): void
    {
        $this->assertTrue(Chronos::createFromDate(2012, 1, 1)->isLeapYear());
    }

    public function testIsLeapYearFalse(): void
    {
        $this->assertFalse(Chronos::createFromDate(2011, 1, 1)->isLeapYear());
    }

    public function testWeekOfMonth(): void
    {
        $this->assertSame(5, Chronos::createFromDate(2012, 9, 30)->weekOfMonth);
        $this->assertSame(4, Chronos::createFromDate(2012, 9, 28)->weekOfMonth);
        $this->assertSame(3, Chronos::createFromDate(2012, 9, 20)->weekOfMonth);
        $this->assertSame(2, Chronos::createFromDate(2012, 9, 8)->weekOfMonth);
        $this->assertSame(1, Chronos::createFromDate(2012, 9, 1)->weekOfMonth);
    }

    public function testWeekOfYearFirstWeek(): void
    {
        $this->assertSame(52, Chronos::createFromDate(2012, 1, 1)->weekOfYear);
        $this->assertSame(1, Chronos::createFromDate(2012, 1, 2)->weekOfYear);
    }

    public function testWeekOfYearLastWeek(): void
    {
        $this->assertSame(52, Chronos::createFromDate(2012, 12, 30)->weekOfYear);
        $this->assertSame(1, Chronos::createFromDate(2012, 12, 31)->weekOfYear);
    }

    public function testGetWeekStartsAt(): void
    {
        $d = Chronos::createFromDate(2012, 12, 31);
        $this->assertSame(Chronos::MONDAY, $d->getWeekStartsAt());

        $d::setWeekStartsAt(Chronos::SUNDAY);
        $this->assertSame(Chronos::SUNDAY, $d->getWeekStartsAt());
    }

    public function testGetWeekEndsAt(): void
    {
        $d = Chronos::createFromDate(2012, 12, 31);
        $this->assertSame(Chronos::SUNDAY, $d->getWeekEndsAt());

        $d::setWeekEndsAt(Chronos::SATURDAY);
        $this->assertSame(Chronos::SATURDAY, $d->getWeekEndsAt());
    }

    public function testGetTimezone(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->timezone->getName());
    }

    public function testGetTz(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->tz->getName());
    }

    public function testGetTimezoneName(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->timezoneName);
    }

    public function testGetTzName(): void
    {
        $dt = Chronos::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->tzName);
    }

    public function testInvalidGetter(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $d = Chronos::now();
        $d->doesNotExit;
    }

    public function testToArray(): void
    {
        $d = Chronos::create(2024, 1, 15, 12, 30, 45, 123456, 'America/Toronto');
        $array = $d->toArray();

        $this->assertSame(2024, $array['year']);
        $this->assertSame(1, $array['month']);
        $this->assertSame(15, $array['day']);
        $this->assertSame(12, $array['hour']);
        $this->assertSame(30, $array['minute']);
        $this->assertSame(45, $array['second']);
        $this->assertSame(123456, $array['microsecond']);
        $this->assertSame('America/Toronto', $array['timezone']);
    }
}
