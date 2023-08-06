<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;

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
        $this->deprecated(function () use ($value) {
            $date = new ChronosDate();
            $new = $date->modify($value);

            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);
            $this->assertSame(0, $new->hour);
            $this->assertSame(0, $new->minute);
            $this->assertSame(0, $new->second);
        });
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
        $this->deprecated(function () use ($method, $value) {
            $date = new ChronosDate();
            $new = $date->{$method}($value);
            $this->assertSame(0, $new->hour);
            $this->assertSame(0, $new->minute);
            $this->assertSame(0, $new->second);
            $this->assertSame('000000', $new->format('u'));
            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);
            $this->assertSame('000000', $date->format('u'));
        });
    }

    /**
     * Test that setTime() ignores microseconds
     */
    public function testSetTimeIgnored()
    {
        // This should have a deprecation but we use it internally quite a bit.
        $date = new ChronosDate();
        $new = $date->setTime(1, 2, 3, 4);
        $this->assertSame(0, $new->hour);
        $this->assertSame(0, $new->minute);
        $this->assertSame(0, $new->second);
        $this->assertSame('000000', $new->format('u'));
        $this->assertSame(0, $date->hour);
        $this->assertSame(0, $date->minute);
        $this->assertSame(0, $date->second);
        $this->assertSame('000000', $date->format('u'));
    }

    /**
     * Test that timestamp methods ignore time changes.
     *
     * @return void
     */
    public function testSetTimestampRemovesTime()
    {
        $this->deprecated(function () {
            $date = new ChronosDate();
            $date->setTimestamp(strtotime('+2 hours +2 minutes'));
            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);

            $date = new ChronosDate();
            $date->timestamp(strtotime('+2 hours +2 minutes'));
            $this->assertSame(0, $date->hour);
            $this->assertSame(0, $date->minute);
            $this->assertSame(0, $date->second);
        });
    }

    public function testStartOfDay()
    {
        $this->deprecated(function () {
            $date = new ChronosDate();
            $this->assertSame('00:00:00', $date->startOfDay()->format('H:i:s'));
        });
    }

    public function testEndOfDay()
    {
        $this->deprecated(function () {
            $date = ChronosDate::create(2001, 1, 1);
            $new = $date->endOfDay();
            $this->assertSame('00:00:00', $new->format('H:i:s'));
            $this->assertSame('2001-01-01', $new->format('Y-m-d'));
        });
    }

    public function testEndOfMonth()
    {
        $this->deprecated(function () {
            $date = ChronosDate::create(2001, 1, 1);
            $new = $date->endOfMonth();
            $this->assertSame('00:00:00', $new->format('H:i:s'));
            $this->assertSame('2001-01-31', $new->format('Y-m-d'));
        });
    }

    public function testEndOfYear()
    {
        $this->deprecated(function () {
            $date = ChronosDate::create(2001, 1, 1);
            $new = $date->endOfYear();
            $this->assertSame('00:00:00', $new->format('H:i:s'));
            $this->assertSame('2001-12-31', $new->format('Y-m-d'));
        });
    }

    public function testEndOfDecade()
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->endOfDecade();
        $this->assertSame('00:00:00', $new->format('H:i:s'));
        $this->assertSame('2009-12-31', $new->format('Y-m-d'));
    }

    public function testEndOfCentury()
    {
        $this->deprecated(function () {
            $date = ChronosDate::create(2001, 1, 1);
            $new = $date->endOfCentury();
            $this->assertSame('00:00:00', $new->format('H:i:s'));
            $this->assertSame('2100-12-31', $new->format('Y-m-d'));
        });
    }

    public function testNextAndPrev()
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->next(3);
        $this->assertSame('00:00:00', $new->format('H:i:s'));
        $this->assertSame('2001-01-03', $new->format('Y-m-d'));

        $new = $date->previous(1);
        $this->assertSame('00:00:00', $new->format('H:i:s'));
        $this->assertSame('2000-12-25', $new->format('Y-m-d'));
    }
}
