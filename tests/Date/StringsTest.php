<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\Date;

use Cake\Chronos\Date;
use TestCase;

class StringsTest extends TestCase
{
    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToString($class)
    {
        $d = Date::now();
        $this->assertSame(Date::now()->toDateString(), '' . $d);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testSetToStringFormat($class)
    {
        $class::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = $class::create(1975, 12, 25);
        $this->assertSame('25th of December, 1975 12:00:00 am', '' . $d);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testResetToStringFormat($class)
    {
        $d = $class::now();
        $class::setToStringFormat('123');
        $class::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), '' . $d);
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToDateString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToFormattedDateString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('00:00:00', $d->toTimeString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToDateTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 00:00:00', $d->toDateTimeString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToDayDateTimeString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 12:00 AM', $d->toDayDateTimeString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToAtomString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toAtomString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToCOOKIEString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        if (\DateTime::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 00:00:00 UTC';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 00:00:00 UTC';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToIso8601String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toIso8601String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRC822String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc822String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRfc850String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 00:00:00 UTC', $d->toRfc850String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRfc1036String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 00:00:00 +0000', $d->toRfc1036String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRfc1123String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc1123String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRfc2822String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRfc2822String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRfc3339String($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toRfc3339String());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToRssString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 00:00:00 +0000', $d->toRssString());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testToW3cString($class)
    {
        $d = $class::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T00:00:00+00:00', $d->toW3cString());
    }
}
