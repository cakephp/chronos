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

class PhpBug72338Test extends TestCase
{
    /**
     * Ensures that $date->format('U') returns unchanged timestamp
     */
    public function testTimestamp()
    {
        $date = Chronos::createFromTimestamp(0)->setTimezone('+02:00');
        $this->assertSame('0', $date->format('U'));
    }

    /**
     * Ensures that date created from string with timezone and with same timezone set by setTimezone() is equal
     */
    public function testEqualSetAndCreate()
    {
        $date = Chronos::createFromTimestamp(0)->setTimezone('+02:00');
        $date1 = new Chronos('1970-01-01T02:00:00+02:00');
        $this->assertSame($date->format('U'), $date1->format('U'));
    }

    /**
     * Ensures that second call to setTimezone() dont changing timestamp
     */
    public function testSecondSetTimezone()
    {
        $date = Chronos::createFromTimestamp(0)->setTimezone('+02:00')->setTimezone('Europe/Moscow');
        $this->assertSame('0', $date->format('U'));
    }
}
