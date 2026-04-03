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

class TimestampAddTest extends TestCase
{
    public function testAddSecondsWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addSecondsWithTimestamp(30);
        $this->assertSame('2024-01-15 12:00:30', $result->format('Y-m-d H:i:s'));
    }

    public function testAddSecondsWithTimestampNegative(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:30', 'UTC');
        $result = $time->addSecondsWithTimestamp(-30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubSecondsWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:30', 'UTC');
        $result = $time->subSecondsWithTimestamp(30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddMinutesWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addMinutesWithTimestamp(30);
        $this->assertSame('2024-01-15 12:30:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddMinutesWithTimestampNegative(): void
    {
        $time = Chronos::parse('2024-01-15 12:30:00', 'UTC');
        $result = $time->addMinutesWithTimestamp(-30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubMinutesWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 12:30:00', 'UTC');
        $result = $time->subMinutesWithTimestamp(30);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddHoursWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 12:00:00', 'UTC');
        $result = $time->addHoursWithTimestamp(2);
        $this->assertSame('2024-01-15 14:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testAddHoursWithTimestampNegative(): void
    {
        $time = Chronos::parse('2024-01-15 14:00:00', 'UTC');
        $result = $time->addHoursWithTimestamp(-2);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testSubHoursWithTimestamp(): void
    {
        $time = Chronos::parse('2024-01-15 14:00:00', 'UTC');
        $result = $time->subHoursWithTimestamp(2);
        $this->assertSame('2024-01-15 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * Test DST transition when clocks go BACK (fall back).
     * Australia/Melbourne changes out of daylight savings on 5th April 2026
     * at 3:00 AM AEDT (+11) -> 2:00 AM AEST (+10)
     */
    public function testAddMinutesWithTimestampAcrossDstFallBack(): void
    {
        $time = Chronos::parse('2026-04-05 09:00:00', 'Australia/Melbourne');

        $this->assertSame('2026-04-05T09:00:00+10:00', $time->toIso8601String());
        $this->assertSame('2026-04-05T00:00:00+11:00', $time->startOfDay()->toIso8601String());

        $diff = $time->diffInMinutes($time->startOfDay());
        $this->assertSame(600, $diff);

        // Using timestamp arithmetic should correctly account for DST
        $result = $time->startOfDay()->addMinutesWithTimestamp(600);
        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test DST transition when clocks go FORWARD (spring forward).
     * America/New_York springs forward on 2nd Sunday of March 2025
     * at 2:00 AM EST (-05) -> 3:00 AM EDT (-04)
     */
    public function testAddMinutesWithTimestampAcrossDstSpringForward(): void
    {
        // March 9, 2025 is the 2nd Sunday of March (DST starts)
        $beforeDst = Chronos::parse('2025-03-09 01:00:00', 'America/New_York');
        $this->assertSame('-05:00', $beforeDst->format('P'));

        // Add 2 hours (120 minutes) using timestamp arithmetic
        // Wall clock would show 3:00 AM (skipping 2:00-3:00)
        $result = $beforeDst->addMinutesWithTimestamp(120);

        // Should be 04:00 AM EDT (not 03:00 AM)
        $this->assertSame('2025-03-09T04:00:00-04:00', $result->toIso8601String());
    }

    /**
     * Test that addMinutes and addMinutesWithTimestamp differ during DST
     */
    public function testAddMinutesVsAddMinutesWithTimestampDuringDst(): void
    {
        // Australia/Melbourne DST ends April 5, 2026 at 3am
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        // Wall clock addition (regular addMinutes)
        $wallClock = $startOfDay->addMinutes(600);

        // Timestamp addition
        $elapsed = $startOfDay->addMinutesWithTimestamp(600);

        // These should differ by 1 hour due to DST transition
        $this->assertSame('2026-04-05T10:00:00+10:00', $wallClock->toIso8601String());
        $this->assertSame('2026-04-05T09:00:00+10:00', $elapsed->toIso8601String());
    }

    /**
     * Test addHoursWithTimestamp across DST
     */
    public function testAddHoursWithTimestampAcrossDst(): void
    {
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        $result = $startOfDay->addHoursWithTimestamp(10);

        // 10 actual hours from midnight should be 09:00 (since we gain an hour at 3am)
        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test addSecondsWithTimestamp across DST
     */
    public function testAddSecondsWithTimestampAcrossDst(): void
    {
        $startOfDay = Chronos::parse('2026-04-05 00:00:00', 'Australia/Melbourne');

        // 10 hours in seconds = 36000
        $result = $startOfDay->addSecondsWithTimestamp(36000);

        $this->assertSame('2026-04-05T09:00:00+10:00', $result->toIso8601String());
    }

    /**
     * Test that diffInMinutes and addMinutesWithTimestamp are inverses
     */
    public function testDiffInMinutesIsInverseOfAddMinutesWithTimestamp(): void
    {
        $time = Chronos::parse('2026-04-05 09:00:00', 'Australia/Melbourne');
        $startOfDay = $time->startOfDay();

        $diff = $time->diffInMinutes($startOfDay);

        $reconstructed = $startOfDay->addMinutesWithTimestamp($diff);

        $this->assertSame($time->toIso8601String(), $reconstructed->toIso8601String());
    }
}
