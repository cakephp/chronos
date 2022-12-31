<?php
declare(strict_types=1);

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
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTimeZone;

/**
 * Test that timezone methods don't do anything to calendar dates.
 */
class TimezoneTest extends TestCase
{
    public static function methodNameProvider()
    {
        return [['tz'], ['timezone'], ['setTimezone']];
    }

    /**
     * Test that all the timezone methods do nothing.
     *
     * @dataProvider methodNameProvider
     */
    public function testNoopOnTimezoneChange($method)
    {
        $this->deprecated(function () use ($method) {
            $tz = new DateTimeZone('Pacific/Honolulu');
            $date = new ChronosDate('2015-01-01');
            $new = $date->{$method}($tz);
            $this->assertSame($new, $date);

            $this->assertNotSame($tz, $new->timezone);
        });
    }

    /**
     * Test that all the timezone methods do nothing.
     *
     * @dataProvider methodNameProvider
     */
    public function testNoopOnTimezoneChangeMutableDate($method)
    {
        $tz = new DateTimeZone('Pacific/Honolulu');
        $date = new MutableDate('2015-01-01');
        $new = $date->{$method}($tz);
        $this->assertSame($new, $date);

        $this->assertNotSame($tz, $date->timezone);
    }
}
