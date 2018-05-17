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
use DateTimeZone;
use TestCase;

/**
 * Test that setting time components fails.
 */
class TimeMutateTest extends TestCase
{
    public function invalidModificationProvider()
    {
        return [
            ['-3 hours'],
            ['-3 minutes'],
            ['-3 seconds'],
            ['+1 hour'],
            ['+1 minute'],
            ['+1 second'],
            ['+1 hours, +9 minutes, -1 second'],
        ];
    }

    /**
     * @dataProvider invalidModificationProvider
     */
    public function testModifyFails($value)
    {
        $date = new Date();
        $new = $date->modify($value);

        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
    }

    /**
     * Provide invalid modifier method calls.
     *
     * @return array
     */
    public function invalidModifierProvider()
    {
        return [
            ['second', 10],
            ['addSeconds', 10],
            ['subSeconds', 10],
            ['minute', 40],
            ['addMinutes', 40],
            ['subMinutes', 40],
            ['hour', 11],
            ['addHours', 11],
            ['subHours', 11],
        ];
    }

    /**
     * @dataProvider invalidModifierProvider
     */
    public function testSetterMethodIsIgnored($method, $value)
    {
        $date = new Date();
        $new = $date->{$method}($value);
        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $new->format('u'));
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
        $this->assertEquals(0, $date->format('u'));
    }

    /**
     * Test that setTime() ignores microseconds
     */
    public function testSetTimeIgnored()
    {
        $date = new Date();
        $new = $date->setTime(1, 2, 3, 4);
        $this->assertEquals(0, $new->hour);
        $this->assertEquals(0, $new->minute);
        $this->assertEquals(0, $new->second);
        $this->assertEquals(0, $new->format('u'));
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
        $this->assertEquals(0, $date->format('u'));
    }

    /**
     * Test that timestamp methods ignore time changes.
     *
     * @return void
     */
    public function testSetTimestampRemovesTime()
    {
        $date = new Date();
        $date->setTimestamp(strtotime('+2 hours +2 minutes'));
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);

        $date = new Date();
        $date->timestamp(strtotime('+2 hours +2 minutes'));
        $this->assertEquals(0, $date->hour);
        $this->assertEquals(0, $date->minute);
        $this->assertEquals(0, $date->second);
    }

    public function testStartOfDay()
    {
        $date = new Date();
        $this->assertEquals('00:00:00', $date->startOfDay()->format('H:i:s'));
    }

    public function testEndOfDay()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->endOfDay();
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2001-01-01', $new->format('Y-m-d'));
    }

    public function testEndOfMonth()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->endOfMonth();
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2001-01-31', $new->format('Y-m-d'));
    }

    public function testEndOfYear()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->endOfYear();
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2001-12-31', $new->format('Y-m-d'));
    }

    public function testEndOfDecade()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->endOfDecade();
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2009-12-31', $new->format('Y-m-d'));
    }

    public function testEndOfCentury()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->endOfCentury();
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2100-12-31', $new->format('Y-m-d'));
    }

    public function testNextAndPrev()
    {
        $date = Date::create(2001, 1, 1);
        $new = $date->next(3);
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2001-01-03', $new->format('Y-m-d'));

        $new = $date->previous(1);
        $this->assertEquals('00:00:00', $new->format('H:i:s'));
        $this->assertEquals('2000-12-25', $new->format('Y-m-d'));
    }
}
