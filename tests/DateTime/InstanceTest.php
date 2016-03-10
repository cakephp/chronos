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

class InstanceTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testInstanceFromDateTime($class)
    {
        $dating = $class::instance(\DateTime::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11'));
        $this->assertDateTime($dating, 1975, 5, 21, 22, 32, 11);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testInstanceFromDateTimeKeepsTimezoneName($class)
    {
        $dating = $class::instance(\DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '1975-05-21 22:32:11'
        )->setTimezone(new \DateTimeZone('America/Vancouver')));
        $this->assertSame('America/Vancouver', $dating->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testInstanceFromDateTimeKeepsMicros($class)
    {
        $micro = 254687;
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.' . $micro);
        $carbon = $class::instance($datetime);
        $this->assertSame($micro, $carbon->micro);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatErrors($class)
    {
        $class::createFromFormat('d/m/Y', "41/02/1900");
        $errors = $class::getLastErrors();
        $expected = [
            "warning_count" => 1,
            "warnings" => [
                10 => "The parsed date was invalid",
            ],
            "error_count" => 0,
            "errors" => [],
        ];
        $this->assertSame($expected, $errors);
    }
}
