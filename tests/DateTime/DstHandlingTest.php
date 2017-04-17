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

class DstHandlingTest extends TestCase
{
    /**
     * Tests that daylight saving hours counts in the right way to different modify arguments
     *
     * @param string $dateString date string wich should be modified
     * @param string $timezone   timezone string in wich $dateString is
     * @param string $modify     modify argument
     * @param string $expected   date, that shoud be maked after modifying $dateString in the format: 'Y-m-d H:i:s P'
     *
     * @dataProvider modifyOnDstChangeProvider
     * @return void
     */
    public function testModifyOnDstChange($className, $dateString, $timezone, $modify, $expected)
    {
        $date = new $className($dateString, $timezone);
        $date = $date->modify($modify);
        $this->assertSame($expected, $date->format('Y-m-d H:i:s P'));
    }

    /**
     * Provider for testModifyOnDstChange
     *
     * DayLight saving hours should be counted only if date is modifying by hours or smaller units.
     */
    public function getModifyOnDstChangeTests($className)
    {
        return [
            [$className, '2014-03-30 00:00:00', 'UTC', '+86400 sec', '2014-03-31 00:00:00 +00:00'],
            [$className, '2014-03-30 00:00:00', 'Europe/London', '+86400 sec', '2014-03-31 01:00:00 +01:00'],
            [$className, '2014-03-30 00:00:00', 'UTC', '+1400 minutes', '2014-03-30 23:20:00 +00:00'],
            [$className, '2014-03-30 00:00:00', 'Europe/London', '+1400 minutes', '2014-03-31 00:20:00 +01:00'],
            [$className, '2014-03-30 00:00:00', 'UTC', '+24 hours', '2014-03-31 00:00:00 +00:00'],
            [$className, '2014-03-30 00:00:00', 'Europe/London', '+24 hours', '2014-03-31 01:00:00 +01:00'],
            [$className, '2014-03-30 00:00:00', 'UTC', '+1 day', '2014-03-31 00:00:00 +00:00'],
            [$className, '2014-03-30 00:00:00', 'Europe/London', '+1 day', '2014-03-31 00:00:00 +01:00'],
            [$className, '2014-03-31 10:00:00', 'UTC', 'midnight', '2014-03-31 00:00:00 +00:00'],
            [$className, '2014-03-31 10:00:00', 'Europe/London', 'midnight', '2014-03-31 00:00:00 +01:00'],
            // should work with any symbol cases
            [$className, '2014-03-30 00:00:00', 'Europe/London', '+86400 SeCs', '2014-03-31 01:00:00 +01:00'],
        ];
    }

    /**
     * Provider for testModifyOnDstChange
     *
     * @return array of test cases for all testing classes returned from classNameProvider
     */
    public function modifyOnDstChangeProvider()
    {
        $tests = [];
        $classes = $this->classNameProvider();
        foreach ($classes as $type => $name) {
            // name from data provider is an array of one element
            $name = $name[0];
            $tests = array_merge($tests, $this->getModifyOnDstChangeTests($name));
        }

        return $tests;
    }
}
