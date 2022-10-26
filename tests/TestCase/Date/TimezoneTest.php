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

use Cake\Chronos\Date;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTimeZone;

/**
 * Test that timezone methods don't do anything to calendar dates.
 */
class TimezoneTest extends TestCase
{
    /**
     * Test that all the timezone methods do nothing.
     */
    public function testNoopOnTimezoneChange()
    {
        $tz = new DateTimeZone('Pacific/Honolulu');
        $date = new Date('2015-01-01');
        $new = $date->setTimezone($tz);
        $this->assertSame($new, $date);
        $this->assertNotSame($tz, $new->timezone);
    }
}
