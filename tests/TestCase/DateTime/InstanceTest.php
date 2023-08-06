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

use Cake\Chronos\Test\TestCase\TestCase;

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
        $this->deprecated(function () use ($class) {
            $class::createFromFormat('d/m/Y', '41/02/1900');
            $errors = $class::getLastErrors();
            $expected = [
                'warning_count' => 1,
                'warnings' => [
                    10 => 'The parsed date was invalid',
                ],
                'error_count' => 0,
                'errors' => [],
            ];
            $this->assertSame($expected, $errors);
        });
    }
}
