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
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;

class StringsTest extends TestCase
{
    public function testToString(): void
    {
        $d = Chronos::now();
        $this->assertSame(Chronos::now()->toDateTimeString(), '' . $d);
    }

    public function testSetToStringFormat(): void
    {
        Chronos::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('25th of December, 1975 2:15:16 pm', '' . $d);
    }

    public function testResetToStringFormat(): void
    {
        $d = Chronos::now();
        Chronos::setToStringFormat('123');
        Chronos::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), '' . $d);
    }

    public function testToDateString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    public function testToFormattedDateString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    public function testToTimeString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('14:15:16', $d->toTimeString());
    }

    public function testToDateTimeString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 14:15:16', $d->toDateTimeString());
    }

    public function testToDateTimeStringWithPaddedZeroes(): void
    {
        $d = Chronos::create(2000, 5, 2, 4, 3, 4);
        $this->assertSame('2000-05-02 04:03:04', $d->toDateTimeString());
    }

    public function testToDayDateTimeString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 2:15 PM', $d->toDayDateTimeString());
    }

    public function testToAtomString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toAtomString());
    }

    public function testToCOOKIEString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        if (DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 14:15:16 EST';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 14:15:16 EST';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    public function testToIso8601String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toIso8601String());
    }

    public function testToRC822String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc822String());
    }

    public function testToRfc850String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 14:15:16 EST', $d->toRfc850String());
    }

    public function testToRfc1036String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc1036String());
    }

    public function testToRfc1123String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc1123String());
    }

    public function testToRfc2822String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc2822String());
    }

    public function testToRfc3339String(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toRfc3339String());
    }

    public function testToRssString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRssString());
    }

    public function testToW3cString(): void
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toW3cString());
    }

    public function testToUnixString(): void
    {
        $time = Chronos::parse('2014-04-20 08:00:00');
        $this->assertSame('1397995200', $time->toUnixString());

        $time = Chronos::parse('2021-12-11 07:00:01');
        $this->assertSame('1639224001', $time->toUnixString());
    }

    public function testToRfc7231String(): void
    {
        $time = Chronos::parse('2014-04-20 08:00:00', 'America/Toronto');
        $this->assertSame('Sun, 20 Apr 2014 12:00:00 GMT', $time->toRfc7231String());
    }

    /**
     * Provides values and expectations for the toQuarter method
     *
     * @return array
     */
    public static function toQuarterProvider(): array
    {
        return [
            ['2007-12-25', 4],
            ['2007-9-25', 3],
            ['2007-3-25', 1],
        ];
    }

    /**
     * testToQuarter method
     *
     * @return void
     */
    #[DataProvider('toQuarterProvider')]
    public function testToQuarter(string $date, int $expected, $range = false): void
    {
        $this->assertSame($expected, (new Chronos($date))->toQuarter());
    }

    public static function toQuarterRangeProvider(): array
    {
        return [
            ['2007-3-25', ['2007-01-01', '2007-03-31']],
            ['2007-5-25', ['2007-04-01', '2007-06-30']],
            ['2007-8-25', ['2007-07-01', '2007-09-30']],
            ['2007-12-25', ['2007-10-01', '2007-12-31']],
        ];
    }

    #[DataProvider('toQuarterRangeProvider')]
    public function testToQuarterRange(string $date, array $expected): void
    {
        $this->assertSame($expected, (new Chronos($date))->toQuarterRange());
        $this->deprecated(function () use ($date, $expected): void {
            $this->assertSame($expected, (new Chronos($date))->toQuarter(true));
        });
    }

    /**
     * Provides values and expectations for the toWeek method
     *
     * @return array
     */
    public static function toWeekProvider(): array
    {
        return [
            ['2007-1-1', 1],
            ['2007-3-25', 12],
            ['2007-12-29', 52],
            ['2007-12-31', 1],
        ];
    }

    /**
     * testToWeek method
     *
     * @return void
     */
    #[DataProvider('toWeekProvider')]
    public function testToWeek(string $date, int $expected): void
    {
        $this->assertSame($expected, (new Chronos($date))->toWeek());
    }

    public function testToNative(): void
    {
        $c = Chronos::now();
        $native = $c->toNative();
        $this->assertSame($c->format(DATE_ATOM), $native->format(DATE_ATOM));
        $this->assertEquals($c->getTimezone(), $native->getTimezone());
        $this->assertEquals($c->format('u'), $native->format('u'));
    }
}
