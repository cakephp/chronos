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
     * @expectedException LogicException
     */
    public function testModifyFails($value)
    {
        $date = new Date();
        $date->modify($value);
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
            ['minute', 40],
            ['hour', 11],
        ];
    }

    /**
     * @dataProvider invalidModifierProvider
     * @expectedException LogicException
     */
    public function testSetterMethodFails($method, $value)
    {
        $date = new Date();
        $date->{$method}($value);
    }
}
