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

class StringsTest extends TestCase
{
    public function testToString()
    {
        $d = Chronos::now();
        $this->assertSame(Chronos::now()->toDateTimeString(), '' . $d);
    }

    public function testSetToStringFormat()
    {
        Chronos::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('25th of December, 1975 2:15:16 pm', '' . $d);
    }

    public function testResetToStringFormat()
    {
        $d = Chronos::now();
        Chronos::setToStringFormat('123');
        Chronos::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), '' . $d);
    }

    public function testToDateString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    public function testToFormattedDateString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    public function testToTimeString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('14:15:16', $d->toTimeString());
    }

    public function testToDateTimeString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 14:15:16', $d->toDateTimeString());
    }

    public function testToDateTimeStringWithPaddedZeroes()
    {
        $d = Chronos::create(2000, 5, 2, 4, 3, 4);
        $this->assertSame('2000-05-02 04:03:04', $d->toDateTimeString());
    }

    public function testToDayDateTimeString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 2:15 PM', $d->toDayDateTimeString());
    }

    public function testToAtomString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toAtomString());
    }

    public function testToCOOKIEString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        if (DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 14:15:16 EST';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 14:15:16 EST';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    public function testToIso8601String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toIso8601String());
    }

    public function testToRC822String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc822String());
    }

    public function testToRfc850String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 14:15:16 EST', $d->toRfc850String());
    }

    public function testToRfc1036String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc1036String());
    }

    public function testToRfc1123String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc1123String());
    }

    public function testToRfc2822String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc2822String());
    }

    public function testToRfc3339String()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toRfc3339String());
    }

    public function testToRssString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRssString());
    }

    public function testToW3cString()
    {
        $d = Chronos::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toW3cString());
    }

    public function testToUnixString()
    {
        $time = Chronos::parse('2014-04-20 08:00:00');
        $this->assertSame('1397995200', $time->toUnixString());

        $time = Chronos::parse('2021-12-11 07:00:01');
        $this->assertSame('1639224001', $time->toUnixString());
    }

    /**
     * Provides values and expectations for the toQuarter method
     *
     * @return array
     */
    public static function toQuarterProvider()
    {
        return [
            ['2007-12-25', 4],
            ['2007-9-25', 3],
            ['2007-3-25', 1],
            ['2007-3-25', ['2007-01-01', '2007-03-31'], true],
            ['2007-5-25', ['2007-04-01', '2007-06-30'], true],
            ['2007-8-25', ['2007-07-01', '2007-09-30'], true],
            ['2007-12-25', ['2007-10-01', '2007-12-31'], true],
        ];
    }

    /**
     * testToQuarter method
     *
     * @dataProvider toQuarterProvider
     * @return void
     */
    public function testToQuarter($date, $expected, $range = false)
    {
        $this->assertSame($expected, (new Chronos($date))->toQuarter($range));
    }

    /**
     * Provides values and expectations for the toWeek method
     *
     * @return array
     */
    public static function toWeekProvider()
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
     * @dataProvider toWeekProvider
     * @return void
     */
    public function testToWeek($date, $expected)
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
