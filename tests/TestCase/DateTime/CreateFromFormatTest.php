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
use DateTimeZone;
use InvalidArgumentException;

class CreateFromFormatTest extends TestCase
{
    public function testCreateFromFormatReturnsInstance()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertTrue($d instanceof Chronos);
    }

    public function testCreateFromFormatWithTimezoneString()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', 'Europe/London');
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromFormatWithTimezone()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', new DateTimeZone('Europe/London'));
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->tzName);
    }

    public function testCreateFromFormatWithMillis()
    {
        $d = Chronos::createFromFormat('Y-m-d H:i:s.u', '1975-05-21 22:32:11.254687');
        $this->assertSame(254687, $d->micro);
    }

    public function testCreateFromFormatInvalidFormat()
    {
        $parseException = null;
        try {
            Chronos::createFromFormat('Y-m-d H:i:s.u', '1975-05-21');
        } catch (InvalidArgumentException $e) {
            $parseException = $e;
        }

        $this->assertNotNull($parseException);
        $this->assertIsArray(Chronos::getLastErrors());
        $this->assertNotEmpty(Chronos::getLastErrors()['errors']);
    }
}
