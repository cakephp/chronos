<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosDatePeriod;
use DateInterval;
use DatePeriod;
use DateTime;

class ChronosDatePeriodTest extends TestCase
{
    public function testChronosPeriod(): void
    {
        $period = new ChronosDatePeriod(new DatePeriod(new DateTime('2025-01-01 00:00:00'), new DateInterval('P1D'), 3));
        $output = [];
        foreach ($period as $key => $value) {
            $output[$key] = $value;
        }
        $this->assertCount(4, $output);
        $this->assertInstanceOf(ChronosDAte::class, $output[0]);
        $this->assertSame('2025-01-01 00:00:00', $output[0]->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(ChronosDate::class, $output[1]);
        $this->assertSame('2025-01-02 00:00:00', $output[1]->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(ChronosDate::class, $output[3]);
        $this->assertSame('2025-01-04 00:00:00', $output[3]->format('Y-m-d H:i:s'));
    }
}
