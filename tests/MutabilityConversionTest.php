<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test;

use Cake\Chronos\Chronos;
use Cake\Chronos\MutableDateTime;
use TestCase;

class MutabilityConversionTest extends TestCase
{
    public function testImmutableInstanceFromMutable()
    {
        $dt1 = MutableDateTime::create(2001, 2, 3, 10, 20, 30);
        $dt2 = Chronos::instance($dt1);
        $this->checkBothInstances($dt1, $dt2);
    }

    public function testMutableInstanceFromImmutable()
    {
        $dt1 = Chronos::create(2001, 2, 3, 10, 20, 30);
        $dt2 = MutableDateTime::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testToImmutable()
    {
        $dt1 = MutableDateTime::create(2001, 2, 3, 10, 20, 30);
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
        $dt2 = MutableDateTime::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testIsMutableMethod()
    {
        $dt1 = MutableDateTime::now();
        $this->assertTrue($dt1->isMutable());

        $dt2 = Chronos::now();
        $this->assertFalse($dt2->isMutable());
    }

    protected function checkBothInstances(MutableDateTime $dt1, Chronos $dt2)
    {
        $this->assertDateTime($dt1, 2001, 2, 3, 10, 20, 30);
        $this->assertInstanceOf(Chronos::class, $dt2);
        $this->assertDateTime($dt2, 2001, 2, 3, 10, 20, 30);
    }
}
