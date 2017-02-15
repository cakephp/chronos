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
namespace Cake\Chronos\Test;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Cake\Chronos\MutableDate;
use Cake\Chronos\MutableDateTime;
use TestCase;

class DebugInfoTest extends TestCase
{
    public function testDateTime()
    {
        $expected = [
            'time' => '2001-02-03 10:20:30.000000',
            'timezone' => 'America/Toronto',
            'hasFixedNow' => false
        ];

        $chronos = Chronos::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $chronos->__debugInfo());

        $mutable = MutableDateTime::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $mutable->__debugInfo());
    }

    public function testDate()
    {
        $expected = [
            'date' => '2001-02-03',
            'hasFixedNow' => false
        ];

        $date = Date::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $date->__debugInfo());

        $mutable = MutableDate::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $mutable->__debugInfo());
    }

    public function testDateTimeWithNow()
    {
        $expected = [
            'time' => '2001-02-03 10:20:30.000000',
            'timezone' => 'America/Toronto',
            'hasFixedNow' => true
        ];

        Chronos::setTestNow(Chronos::now());
        $chronos = Chronos::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $chronos->__debugInfo());

        MutableDateTime::setTestNow(Chronos::now());
        $mutable = MutableDateTime::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $mutable->__debugInfo());
    }

    public function testDateWithNow()
    {
        $expected = [
            'date' => '2001-02-03',
            'hasFixedNow' => true
        ];

        Date::setTestNow(Chronos::now());
        $date = Date::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $date->__debugInfo());

        MutableDate::setTestNow(Chronos::now());
        $mutable = MutableDate::create(2001, 2, 3, 10, 20, 30);
        $this->assertEquals($expected, $mutable->__debugInfo());
    }
}
