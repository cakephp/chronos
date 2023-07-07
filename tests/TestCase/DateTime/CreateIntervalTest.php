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

class CreateIntervalTest extends TestCase
{
    public function testCreateInterval(): void
    {
        $interval = Chronos::createInterval(hours: 2);
        $this->assertDateInterval($interval, hours: 2);

        $interval = Chronos::createInterval(microseconds: 2);
        $this->assertDateInterval($interval, seconds: 0, microseconds: 0.000002);
    }

    public function testRollover(): void
    {
        $i = Chronos::createInterval(microseconds: 1);
        $this->assertDateInterval($i, days: 0, hours: 0, minutes: 0, seconds: 0, microseconds: 0.000001);

        $i = Chronos::createInterval(days: 1, hours: 25, minutes: 61, seconds: 61, microseconds: 1_000_001);
        $this->assertDateInterval($i, days: 2, hours: 2, minutes: 2, seconds: 2, microseconds: 0.000001);

        $i = Chronos::createInterval(days: null, hours: 25, minutes: null, seconds: null, microseconds: 1_000_001);
        $this->assertDateInterval($i, days: 1, hours: 1, minutes: 0, seconds: 1, microseconds: 0.000001);

        $i = Chronos::createInterval(days: null, hours: null, minutes: 61, seconds: null, microseconds: null);
        $this->assertDateInterval($i, days: 0, hours: 1, minutes: 1, seconds: 0, microseconds: 0.0);
    }
}
