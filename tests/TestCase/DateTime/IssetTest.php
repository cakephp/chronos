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

use Cake\Chronos\Chronos;
use Cake\Chronos\Test\TestCase\TestCase;

class IssetTest extends TestCase
{
    public function testIssetReturnFalseForUnknownProperty()
    {
        $this->assertFalse(isset(Chronos::create(1234, 5, 6, 7, 8, 9)->sdfsdfss));
    }

    public function testIssetReturnTrueForProperties()
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
            'half',
        ];

        foreach ($properties as $property) {
            $this->assertTrue(isset(Chronos::create(1234, 5, 6, 7, 8, 9)->$property));
        }
    }
}
