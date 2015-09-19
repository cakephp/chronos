<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Carbon;
use TestFixture;

class IssetTest extends TestFixture
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
        $properties = array(
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
        );

        foreach ($properties as $property) {
            $this->assertTrue(isset($class::create(1234, 5, 6, 7, 8, 9)->$property));
        }
    }
}
