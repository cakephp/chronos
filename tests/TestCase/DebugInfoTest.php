<?php
declare(strict_types=1);

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
namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;

class DebugInfoTest extends TestCase
{
    public function testDateTime()
    {
        $expected = [
            'hasFixedNow' => false,
            'time' => '2001-02-03 10:20:30.000000',
            'timezone' => 'America/Toronto',
        ];

        $chronos = Chronos::create(2001, 2, 3, 10, 20, 30);
        $this->assertSame($expected, $chronos->__debugInfo());
    }

    public function testDate()
    {
        $expected = [
            'hasFixedNow' => false,
            'date' => '2001-02-03',
        ];

        $date = Date::create(2001, 2, 3, 10, 20, 30);
        $this->assertSame($expected, $date->__debugInfo());
    }

    public function testDateTimeWithNow()
    {
        $expected = [
            'hasFixedNow' => true,
            'time' => '2001-02-03 10:20:30.000000',
            'timezone' => 'America/Toronto',
        ];

        Chronos::setTestNow(Chronos::now());
        $chronos = Chronos::create(2001, 2, 3, 10, 20, 30);
        $this->assertSame($expected, $chronos->__debugInfo());
    }

    public function testDateWithNow()
    {
        $expected = [
            'hasFixedNow' => true,
            'date' => '2001-02-03',
        ];

        Date::setTestNow(Chronos::now());
        $date = Date::create(2001, 2, 3, 10, 20, 30);
        $this->assertSame($expected, $date->__debugInfo());
    }
}
