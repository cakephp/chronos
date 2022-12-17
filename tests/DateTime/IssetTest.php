<?php
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

namespace Cake\Chronos\Test\DateTime;

use TestCase;

class IssetTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIssetReturnFalseForUnknownProperty($class)
    {
        $this->assertFalse(isset($class::create(1234, 5, 6, 7, 8, 9)->sdfsdfss));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testIssetReturnTrueForProperties($class)
    {
        $properties = [
            'year',
            'month',
            'day',
            'hour',
            'minute',
            'second',
            'dayOfWeek',
            'dayOfYear',
            'daysInMonth',
            'timestamp',
            'age',
            'quarter',
            'dst',
            'offset',
            'offsetHours',
            'timezone',
            'timezoneName',
            'tz',
            'tzName',
        ];

        foreach ($properties as $property) {
            $this->assertTrue(isset($class::create(1234, 5, 6, 7, 8, 9)->$property));
        }
    }
}
