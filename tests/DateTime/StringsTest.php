<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Carbon;
use TestCase;

class StringsTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToString($class)
    {
        $d = $class::now();
        $this->assertSame($class::now()->toDateTimeString(), ''.$d);
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSetToStringFormat($class)
    {
        $class::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('25th of December, 1975 2:15:16 pm', ''.$d);
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testResetToStringFormat($class)
    {
        $d = $class::now();
        $class::setToStringFormat('123');
        $class::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), ''.$d);
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToDateString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToFormattedDateString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToLocalizedFormattedDateString($class)
    {
        /****************

      Working out a Travis issue on how to set a different locale
      other than EN to test this.


      $cache = setlocale(LC_TIME, 0);
      setlocale(LC_TIME, 'German');
      $d = $class::create(1975, 12, 25, 14, 15, 16);
      $this->assertSame('Donnerstag 25 Dezember 1975', $d->formatLocalized('%A %d %B %Y'));
      setlocale(LC_TIME, $cache);

      *****************/
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToLocalizedFormattedTimezonedDateString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16, 'Europe/London');
        $this->assertSame('Thursday 25 December 1975 14:15', $d->formatLocalized('%A %d %B %Y %H:%M'));
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('14:15:16', $d->toTimeString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToDateTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 14:15:16', $d->toDateTimeString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToDateTimeStringWithPaddedZeroes($class)
    {
        $d = $class::create(2000, 5, 2, 4, 3, 4);
        $this->assertSame('2000-05-02 04:03:04', $d->toDateTimeString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToDayDateTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 2:15 PM', $d->toDayDateTimeString());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToAtomString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toAtomString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToCOOKIEString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        if (\DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 14:15:16 EST';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 14:15:16 EST';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToIso8601String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-0500', $d->toIso8601String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRC822String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc822String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRfc850String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 14:15:16 EST', $d->toRfc850String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRfc1036String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc1036String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRfc1123String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc1123String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRfc2822String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc2822String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRfc3339String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toRfc3339String());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToRssString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRssString());
    }
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testToW3cString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toW3cString());
    }
}
