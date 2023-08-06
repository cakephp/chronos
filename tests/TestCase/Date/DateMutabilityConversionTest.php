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
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase\TestCase;

class DateMutabilityConversionTest extends TestCase
{
    public function testImmutableInstanceFromMutable()
    {
        $this->deprecated(function () {
            $dt1 = MutableDate::create(2001, 2, 3, 10, 20, 30);
            $dt2 = ChronosDate::instance($dt1);
            $this->checkBothInstances($dt1, $dt2);
        });
    }

    public function testMutableInstanceFromImmutable()
    {
        $dt1 = ChronosDate::create(2001, 2, 3, 10, 20, 30);
        $dt2 = MutableDate::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testToImmutable()
    {
        $this->deprecated(function () {
            $dt1 = MutableDate::create(2001, 2, 3, 10, 20, 30);
            $dt2 = $dt1->toImmutable();
            $this->checkBothInstances($dt1, $dt2);
        });
    }

    public function testToMutable()
    {
        $this->deprecated(function () {
            $dt1 = ChronosDate::create(2001, 2, 3, 10, 20, 30);
            $dt2 = $dt1->toMutable();
            $this->checkBothInstances($dt2, $dt1);
        });
    }

    public function testMutableFromImmutable()
    {
        $dt1 = ChronosDate::create(2001, 2, 3, 10, 20, 30);
        $dt2 = MutableDate::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testIsMutableMethod()
    {
        $this->deprecated(function () {
            $dt1 = MutableDate::now();
            $this->assertTrue($dt1->isMutable());
        });

        $this->deprecated(function () {
            $dt2 = ChronosDate::now();
            $this->assertFalse($dt2->isMutable());
        });
    }

    public function testSetISODate()
    {
        $date = ChronosDate::create(2001, 1, 1);
        $new = $date->setISODate(2023, 17, 3);
        $this->assertSame('00:00:00', $new->format('H:i:s'));
        $this->assertSame('2023-04-26', $new->format('Y-m-d'));
    }

    protected function checkBothInstances(MutableDate $dt1, ChronosDate $dt2)
    {
        $this->assertDateTime($dt1, 2001, 2, 3, 0, 0, 0);
        $this->assertInstanceOf(ChronosDate::class, $dt2);
        $this->assertDateTime($dt2, 2001, 2, 3, 0, 0, 0);
    }
}
