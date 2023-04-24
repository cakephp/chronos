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

use Cake\Chronos\Test\TestCase\TestCase;

class CreateFromFormatTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatReturnsInstance($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertTrue($d instanceof $class);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithTimezoneString($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', 'Europe/London');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithTimezone($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', new \DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromFormatWithMillis($class)
    {
        $d = $class::createFromFormat('Y-m-d H:i:s.u', '1975-05-21 22:32:11.254687');
        $this->assertSame(254687, $d->micro);
    }
}
