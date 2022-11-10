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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\Test\TestCase\TestCase;

class CreateIntervalTest extends TestCase
{
    public function testCreateInterval(): void
    {
        $interval = Chronos::createInterval(0, 0, 0, 0, 2);
        $this->assertDateInterval($interval, null, null, null, 2);

        $interval = Chronos::createInterval(0, 0, 0, 0, 0, 0, 0, 2);
        $this->assertDateInterval($interval, 0, 0, 0, 0, 0, 0, 0.000002);
    }
}
