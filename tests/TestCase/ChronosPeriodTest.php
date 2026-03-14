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

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use InvalidArgumentException;

class ChronosPeriodTest extends TestCase
{
    public function testChronosPeriod(): void
    {
        $period = new ChronosPeriod(new DatePeriod(new DateTime('2025-01-01 00:00:00'), new DateInterval('PT1H'), 3));
        $output = [];
        foreach ($period as $key => $value) {
            $output[$key] = $value;
        }
        $this->assertCount(4, $output);
        $this->assertInstanceOf(Chronos::class, $output[0]);
        $this->assertSame('2025-01-01 00:00:00', $output[0]->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(Chronos::class, $output[1]);
        $this->assertSame('2025-01-01 01:00:00', $output[1]->format('Y-m-d H:i:s'));
    }

    public function testZeroIntervalThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a period with a zero interval');

        $period = new DatePeriod(
            new DateTime('2025-01-01'),
            new DateInterval('PT0S'),
            new DateTime('2025-01-02'),
        );
        new ChronosPeriod($period);
    }

    public function testZeroIntervalAllZeroComponents(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $interval = new DateInterval('P0D');
        $period = new DatePeriod(
            new DateTime('2025-01-01'),
            $interval,
            new DateTime('2025-01-02'),
        );
        new ChronosPeriod($period);
    }
}
