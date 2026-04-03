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

class DaylightSavingsAddTest extends TestCase
{
    public function testDayLightSavingsTransition(): void
    {
        // Australia/Melbourne changes out of daylight savings 5th April 2026
        $time = Chronos::parse('2026-04-05 09:00:00', 'Australia/Melbourne');

        $this->assertSame('2026-04-05T09:00:00+10:00', $time->toIso8601String());
        $this->assertSame('2026-04-05T00:00:00+11:00', $time->startOfDay()->toIso8601String());

        $this->assertSame(600, $time->diffInMinutes($time->startOfDay()));

        $this->assertSame('2026-04-05T09:00:00+10:00', $time->startOfDay()->addMinutes(600)->toIso8601String());
    }
}
