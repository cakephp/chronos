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

class PhpBug72338Test extends TestCase
{

    /**
     * Ensures that $date->format('U') returns unchanged timestamp
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testTimestamp($class)
    {
        $date = $class::createFromTimestamp(0)->setTimezone('+02:00');
        $this->assertSame('0', $date->format('U'));
    }

    /**
     * Ensures that date created from string with timezone and with same timezone set by setTimezone() is equal
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testEqualSetAndCreate($class)
    {
        $date = $class::createFromTimestamp(0)->setTimezone('+02:00');
        $date1 = new $class('1970-01-01T02:00:00+02:00');
        $this->assertSame($date->format('U'), $date1->format('U'));
    }

    /**
     * Ensures that second call to setTimezone() dont changing timestamp
     *
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondSetTimezone($class)
    {
        $date = $class::createFromTimestamp(0)->setTimezone('+02:00')->setTimezone('Europe/Moscow');
        $this->assertSame('0', $date->format('U'));
    }
}
