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
}
