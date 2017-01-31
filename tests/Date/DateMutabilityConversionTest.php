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
namespace Cake\Chronos\Test\Date;

use Cake\Chronos\Date;
use Cake\Chronos\MutableDate;
use TestCase;

class DateMutabilityConversionTest extends TestCase
{
    public function testImmutableInstanceFromMutable()
    {
        $dt1 = MutableDate::create(2001, 2, 3, 10, 20, 30);
        $dt2 = Date::instance($dt1);
        $this->checkBothInstances($dt1, $dt2);
    }

    public function testMutableInstanceFromImmutable()
    {
        $dt1 = Date::create(2001, 2, 3, 10, 20, 30);
        $dt2 = MutableDate::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testToImmutable()
    {
        $dt1 = MutableDate::create(2001, 2, 3, 10, 20, 30);
        $dt2 = $dt1->toImmutable();
        $this->checkBothInstances($dt1, $dt2);
    }

    public function testToMutable()
    {
        $dt1 = Date::create(2001, 2, 3, 10, 20, 30);
        $dt2 = $dt1->toMutable();
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testMutableFromImmutable()
    {
        $dt1 = Date::create(2001, 2, 3, 10, 20, 30);
        $dt2 = MutableDate::instance($dt1);
        $this->checkBothInstances($dt2, $dt1);
    }

    public function testIsMutableMethod()
    {
        $dt1 = MutableDate::now();
        $this->assertTrue($dt1->isMutable());

        $dt2 = Date::now();
        $this->assertFalse($dt2->isMutable());
    }

    protected function checkBothInstances(MutableDate $dt1, Date $dt2)
    {
        $this->assertDateTime($dt1, 2001, 2, 3, 0, 0, 0);
        $this->assertInstanceOf(Date::class, $dt2);
        $this->assertDateTime($dt2, 2001, 2, 3, 0, 0, 0);
    }
}
