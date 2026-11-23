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

class ElapsedTimeAddTest extends TestCase
{
    public function testAddElapsedSeconds(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addElapsedSeconds(30);
        $this->assertSame('2024-01-15 12:00:30', $result->format('Y-m-d H:i:s'));
    }

    public function testAddElapsedSecondsNegative(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:30', 'UTC');
        $result = $time->addElapsedSeconds(-30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubElapsedSeconds(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:30', 'UTC');
        $result = $time->subElapsedSeconds(30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddElapsedMinutes(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addElapsedMinutes(30);
        $this->assertSame('2024-01-15 12:30:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddElapsedMinutesNegative(): void
    {
        $time = Chronos::parse('2024-01-15 12:30:00', 'UTC');
        $result = $time->addElapsedMinutes(-30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubElapsedMinutes(): void
    {
        $time = Chronos::parse('2024-01-15 12:30:00', 'UTC');
        $result = $time->subElapsedMinutes(30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddElapsedHours(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addElapsedHours(2);
        $this->assertSame('2024-01-15 14:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddElapsedHoursNegative(): void
    {
        $time = Chronos::parse('2024-01-15 14:00:00', 'UTC');
        $result = $time->addElapsedHours(-2);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubElapsedHours(): void
    {
        $time = Chronos::parse('2024-01-15 14:00:00', 'UTC');
        $result = $time->subElapsedHours(2);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * Test DST transition when clocks go BACK (fall back).
     * Australia/Melbourne changes out of daylight saving on 5th April 2026
     * at 3:00 AM AEDT (+11) -> 2:00 AM AEST (+10)
     */
    public function testAddElapsedMinutesAcrossDstFallBack(): void
    {
        $time = Chronos::parse('2026-04-05 09:00:00', 'Australia/Melbourne');

        $this->assertSame('2026-04-05T09:00:00+10:00', $time->toIso8601String());
        $this->assertSame('2026-04-05T00:00:00+11:00', $time->startOfDay()->toIso8601String());

        $diff = $time->diffInMinutes($time->startOfDay());
        $this->assertSame(600, $diff);

        // Using elapsed time should correctly account for DST
        $result = $time->startOfDay()->addElapsedMinutes(600);
        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test DST transition when clocks go FORWARD (spring forward).
     * America/New_York springs forward on 2nd Sunday of March 2025
     * at 2:00 AM EST (-05) -> 3:00 AM EDT (-04)
     */
    public function testAddElapsedMinutesAcrossDstSpringForward(): void
    {
        // March 9, 2025 is the 2nd Sunday of March (DST starts)
        $beforeDst = Chronos::parse('2025-03-09 01:00:00', 'America/New_York');
        $this->assertSame('-05:00', $beforeDst->format('P'));

        // Add 2 hours (120 minutes) using elapsed time
        // Wall clock would show 3:00 AM (skipping 2:00-3:00)
        $result = $beforeDst->addElapsedMinutes(120);

        // Should be 04:00 AM EDT (not 03:00 AM)
        $this->assertSame('2025-03-09T04:00:00-04:00', $result->toIso8601String());
    }

    /**
     * Test that addMinutes and addElapsedMinutes differ during DST
     */
    public function testAddMinutesVsAddElapsedMinutesDuringDst(): void
    {
        // Australia/Melbourne DST ends April 5, 2026 at 3am
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        // Wall clock addition (regular addMinutes)
        $wallClock = $startOfDay->addMinutes(600);

        // Elapsed time addition
        $elapsed = $startOfDay->addElapsedMinutes(600);

        // These should differ by 1 hour due to DST transition
        $this->assertSame('2026-04-05T10:00:00+10:00', $wallClock->toIso8601String());
        $this->assertSame('2026-04-05T09:00:00+10:00', $elapsed->toIso8601String());
    }

    /**
     * Test addElapsedHours across DST
     */
    public function testAddElapsedHoursAcrossDst(): void
    {
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        $result = $startOfDay->addElapsedHours(10);

        // 10 actual hours from midnight should be 09:00 (since we gain an hour at 3am)
        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test addElapsedSeconds across DST
     */
    public function testAddElapsedSecondsAcrossDst(): void
    {
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        // 10 hours in seconds = 36000
        $result = $startOfDay->addElapsedSeconds(36000);

        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test that diffInMinutes and addElapsedMinutes are inverses
     */
    public function testDiffInMinutesIsInverseOfAddElapsedMinutes(): void
    {
        $time = Chronos::parse('2026-04-05 09:00:00', 'Australia/Melbourne');
        $startOfDay = $time->startOfDay();

        $diff = $time->diffInMinutes($startOfDay);

        $reconstructed = $startOfDay->addElapsedMinutes($diff);

        $this->assertSame($time->toIso8601String(), $reconstructed->toIso8601String());
    }
}
