<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cake\Chronos\Carbon;
use Cake\Chronos\Chronos;

class MutabilityConversionTest extends TestCase
{
    public function testImmutableInstanceFromMutable()
    {
        $dt1 = Carbon::create(2001, 2, 3, 10, 20, 30);
        $dt2 = Chronos::instance($dt1);
        $this->checkBothInstances($dt1, $dt2);
    }

    public function testMutableInstanceFromImmutable()
    {
        $dt1 = Chronos::create(2001, 2, 3, 10, 20, 30);
        $dt2 = Carbon::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testToImmutable()
    {
        $dt1 = Carbon::create(2001, 2, 3, 10, 20, 30);
        $dt2 = $dt1->toImmutable();
        $this->checkBothInstances($dt1, $dt2);
    }

    public function testToMutable()
    {
        $dt1 = Chronos::create(2001, 2, 3, 10, 20, 30);
        $dt2 = $dt1->toMutable();
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testMutableFromImmutable()
    {
        $dt1 = Chronos::create(2001, 2, 3, 10, 20, 30);
        $dt2 = Carbon::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testIsMutableMethod()
    {
        $dt1 = Carbon::now();
        $this->assertTrue($dt1->isMutable());

        $dt2 = Chronos::now();
        $this->assertFalse($dt2->isMutable());
    }

    protected function checkBothInstances(Carbon $dt1, Chronos $dt2)
    {
        $this->assertDateTime($dt1, 2001, 2, 3, 10, 20, 30);
        $this->assertInstanceOf(Chronos::class, $dt2);
        $this->assertDateTime($dt2, 2001, 2, 3, 10, 20, 30);
    }
}
