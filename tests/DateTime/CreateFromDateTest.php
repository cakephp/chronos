<?php
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

namespace Cake\Chronos\Test\DateTime;

use TestCase;

class CreateFromDateTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDefaults($class)
    {
        $d = $class::createFromDate();
        $this->assertSame($d->timestamp, $class::create(null, null, null, null, null, null)->timestamp);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDate($class)
    {
        $d = $class::createFromDate(1975, 5, 21);
        $this->assertDateTime($d, 1975, 5, 21);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithYear($class)
    {
        $d = $class::createFromDate(1975);
        $this->assertSame(1975, $d->year);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithMonth($class)
    {
        $d = $class::createFromDate(null, 5);
        $this->assertSame(5, $d->month);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDay($class)
    {
        $d = $class::createFromDate(null, null, 21);
        $this->assertSame(21, $d->day);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithTimezone($class)
    {
        $d = $class::createFromDate(1975, 5, 21, 'Europe/London');
        $this->assertDateTime($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateWithDateTimeZone($class)
    {
        $d = $class::createFromDate(1975, 5, 21, new \DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->tzName);
    }
}
