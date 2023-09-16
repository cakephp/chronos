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
use DateTimeImmutable;
use DateTimeZone;

class ComparisonTest extends TestCase
{
    public function testGetSetWeekendDays()
    {
        $expected = [Chronos::SATURDAY, Chronos::SUNDAY];
        $this->assertSame($expected, Chronos::getWeekendDays());

        $replace = [Chronos::SUNDAY];
        Chronos::setWeekendDays($replace);
        $this->assertSame($replace, Chronos::getWeekendDays());

        Chronos::setWeekendDays($expected);
    }

    public function testEquals()
    {
        $left = Chronos::create(2000, 1, 1, 0, 0, 0);
        $this->assertTrue($left == new Chronos('2000-01-01 00:00:00'));
        $this->assertTrue($left->equals(new Chronos('2000-01-01 00:00:00')));
        $this->assertTrue($left->equals(new DateTimeImmutable('2000-01-01 00:00:00')));

        $this->assertFalse($left == new Chronos('2000-01-02 00:00:00'));
        $this->assertFalse($left->equals(new Chronos('2000-01-02 00:00:00')));
        $this->assertFalse($left->equals(new DateTimeImmutable('2000-01-02 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left == new Chronos('2000-01-01 9:00:00', 'America/Vancouver'));
        $this->assertTrue($left->equals(new Chronos('2000-01-01 9:00:00', 'America/Vancouver')));
        $this->assertTrue($left->equals(new DateTimeImmutable('2000-01-01 9:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left == new Chronos('2000-01-01 12:00:00', 'America/Vancouver'));
        $this->assertFalse($left->equals(new Chronos('2000-01-01 12:00:00', 'America/Vancouver')));
        $this->assertFalse($left->equals(new DateTimeImmutable('2000-01-01 12:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testNotEquals()
    {
        $left = Chronos::create(2000, 1, 1, 0, 0, 0);
        $this->assertTrue($left != new Chronos('2000-01-02 00:00:00'));
        $this->assertTrue($left->notEquals(new Chronos('2000-01-02 00:00:00')));
        $this->assertTrue($left->notEquals(new DateTimeImmutable('2000-01-02 00:00:00')));

        $this->assertFalse($left != new Chronos('2000-01-01 00:00:00'));
        $this->assertFalse($left->notEquals(new Chronos('2000-01-01 00:00:00')));
        $this->assertFalse($left->notEquals(new DateTimeImmutable('2000-01-01 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left != new Chronos('2000-01-01 12:00:00', 'America/Vancouver'));
        $this->assertTrue($left->notEquals(new Chronos('2000-01-01 12:00:00', 'America/Vancouver')));
        $this->assertTrue($left->notEquals(new DateTimeImmutable('2000-01-01 12:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left != new Chronos('2000-01-01 9:00:00', 'America/Vancouver'));
        $this->assertFalse($left->notEquals(new Chronos('2000-01-01 9:00:00', 'America/Vancouver')));
        $this->assertFalse($left->notEquals(new DateTimeImmutable('2000-01-01 9:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testGreaterThan()
    {
        $left = Chronos::create(2000, 1, 2, 0, 0, 0);
        $this->assertTrue($left > new Chronos('2000-01-01 00:00:00'));
        $this->assertTrue($left->greaterThan(new Chronos('2000-01-01 00:00:00')));
        $this->assertTrue($left->greaterThan(new DateTimeImmutable('2000-01-01 00:00:00')));

        $this->assertFalse($left > new Chronos('2000-01-03 00:00:00'));
        $this->assertFalse($left->greaterThan(new Chronos('2000-01-03 00:00:00')));
        $this->assertFalse($left->greaterThan(new DateTimeImmutable('2000-01-03 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left > new Chronos('2000-01-01 08:00:00', 'America/Vancouver'));
        $this->assertTrue($left->greaterThan(new Chronos('2000-01-01 08:00:00', 'America/Vancouver')));
        $this->assertTrue($left->greaterThan(new DateTimeImmutable('2000-01-01 08:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left > new Chronos('2000-01-01 09:00:00', 'America/Vancouver'));
        $this->assertFalse($left->greaterThan(new Chronos('2000-01-01 09:00:00', 'America/Vancouver')));
        $this->assertFalse($left->greaterThan(new DateTimeImmutable('2000-01-01 09:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testGreaterThanOrEqual()
    {
        $left = Chronos::create(2000, 1, 2, 0, 0, 0);
        $this->assertTrue($left >= new Chronos('2000-01-01 00:00:00'));
        $this->assertTrue($left->greaterThanOrEquals(new Chronos('2000-01-01 00:00:00')));
        $this->assertTrue($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-01 00:00:00')));

        $this->assertTrue($left >= new Chronos('2000-01-02 00:00:00'));
        $this->assertTrue($left->greaterThanOrEquals(new Chronos('2000-01-02 00:00:00')));
        $this->assertTrue($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-02 00:00:00')));

        $this->assertFalse($left >= new Chronos('2000-01-03 00:00:00'));
        $this->assertFalse($left->greaterThanOrEquals(new Chronos('2000-01-03 00:00:00')));
        $this->assertFalse($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-03 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left >= new Chronos('2000-01-01 09:00:00', 'America/Vancouver'));
        $this->assertTrue($left->greaterThanOrEquals(new Chronos('2000-01-01 09:00:00', 'America/Vancouver')));
        $this->assertTrue($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-01 09:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertTrue($left >= new Chronos('2000-01-01 08:00:00', 'America/Vancouver'));
        $this->assertTrue($left->greaterThanOrEquals(new Chronos('2000-01-01 08:00:00', 'America/Vancouver')));
        $this->assertTrue($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-01 08:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left >= new Chronos('2000-01-01 10:00:00', 'America/Vancouver'));
        $this->assertFalse($left->greaterThanOrEquals(new Chronos('2000-01-01 10:00:00', 'America/Vancouver')));
        $this->assertFalse($left->greaterThanOrEquals(new DateTimeImmutable('2000-01-01 10:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testLessThan()
    {
        $left = Chronos::create(2000, 1, 1, 0, 0, 0);
        $this->assertTrue($left < new Chronos('2000-01-02 00:00:00'));
        $this->assertTrue($left->lessThan(new Chronos('2000-01-02 00:00:00')));
        $this->assertTrue($left->lessThan(new DateTimeImmutable('2000-01-02 00:00:00')));

        $this->assertFalse($left < new Chronos('2000-01-01 00:00:00'));
        $this->assertFalse($left->lessThan(new Chronos('2000-01-01 00:00:00')));
        $this->assertFalse($left->lessThan(new DateTimeImmutable('2000-01-01 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left < new Chronos('2000-01-01 12:00:00', 'America/Vancouver'));
        $this->assertTrue($left->lessThan(new Chronos('2000-01-01 12:00:00', 'America/Vancouver')));
        $this->assertTrue($left->lessThan(new DateTimeImmutable('2000-01-01 12:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left < new Chronos('2000-01-01 09:00:00', 'America/Vancouver'));
        $this->assertFalse($left->lessThan(new Chronos('2000-01-01 09:00:00', 'America/Vancouver')));
        $this->assertFalse($left->lessThan(new DateTimeImmutable('2000-01-01 09:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testLessThanOrEqual()
    {
        $left = Chronos::create(2000, 1, 2, 0, 0, 0);
        $this->assertTrue($left <= new Chronos('2000-01-03 00:00:00'));
        $this->assertTrue($left->lessThanOrEquals(new Chronos('2000-01-03 00:00:00')));
        $this->assertTrue($left->lessThanOrEquals(new DateTimeImmutable('2000-01-03 00:00:00')));

        $this->assertTrue($left <= new Chronos('2000-01-02 00:00:00'));
        $this->assertTrue($left->lessThanOrEquals(new Chronos('2000-01-02 00:00:00')));
        $this->assertTrue($left->lessThanOrEquals(new DateTimeImmutable('2000-01-02 00:00:00')));

        $this->assertFalse($left <= new Chronos('2000-01-01 00:00:00'));
        $this->assertFalse($left->lessThanOrEquals(new Chronos('2000-01-01 00:00:00')));
        $this->assertFalse($left->lessThanOrEquals(new DateTimeImmutable('2000-01-01 00:00:00')));

        $left = Chronos::create(2000, 1, 1, 12, 0, 0, 0, 'America/Toronto');
        $this->assertTrue($left <= new Chronos('2000-01-01 10:00:00', 'America/Vancouver'));
        $this->assertTrue($left->lessThanOrEquals(new Chronos('2000-01-01 10:00:00', 'America/Vancouver')));
        $this->assertTrue($left->lessThanOrEquals(new DateTimeImmutable('2000-01-01 10:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertTrue($left <= new Chronos('2000-01-01 09:00:00', 'America/Vancouver'));
        $this->assertTrue($left->lessThanOrEquals(new Chronos('2000-01-01 09:00:00', 'America/Vancouver')));
        $this->assertTrue($left->lessThanOrEquals(new DateTimeImmutable('2000-01-01 09:00:00', new DateTimeZone('America/Vancouver'))));

        $this->assertFalse($left <= new Chronos('2000-01-01 08:00:00', 'America/Vancouver'));
        $this->assertFalse($left->lessThanOrEquals(new Chronos('2000-01-01 08:00:00', 'America/Vancouver')));
        $this->assertFalse($left->lessThanOrEquals(new DateTimeImmutable('2000-01-01 08:00:00', new DateTimeZone('America/Vancouver'))));
    }

    public function testBetween()
    {
        $date = new Chronos('2000-01-15 00:00:00');
        $this->assertTrue($date->between(new Chronos('2000-01-14 00:00:00'), new Chronos('2000-01-15 00:00:00')));
        $this->assertTrue($date->between(new DateTimeImmutable('2000-01-14 00:00:00'), new DateTimeImmutable('2000-01-15 00:00:00')));

        $this->assertTrue($date->between(new Chronos('2000-01-14 00:00:00'), new Chronos('2000-01-16 00:00:00'), false));
        $this->assertTrue($date->between(new DateTimeImmutable('2000-01-14 00:00:00'), new DateTimeImmutable('2000-01-16 00:00:00'), false));

        $this->assertFalse($date->between(new Chronos('2000-01-16 00:00:00'), new Chronos('2000-01-17 00:00:00')));
        $this->assertFalse($date->between(new DateTimeImmutable('2000-01-16 00:00:00'), new DateTimeImmutable('2000-01-17 00:00:00')));

        $this->assertFalse($date->between(new Chronos('2000-01-14 00:00:00'), new Chronos('2000-01-15 00:00:00'), false));
        $this->assertFalse($date->between(new DateTimeImmutable('2000-01-14 00:00:00'), new DateTimeImmutable('2000-01-15 00:00:00'), false));

        // switched
        $this->assertTrue($date->between(new Chronos('2000-01-16 00:00:00'), new Chronos('2000-01-14 00:00:00'), false));
        $this->assertTrue($date->between(new DateTimeImmutable('2000-01-16 00:00:00'), new DateTimeImmutable('2000-01-14 00:00:00'), false));

        $this->assertFalse($date->between(new Chronos('2000-01-15 00:00:00'), new Chronos('2000-01-14 00:00:00'), false));
        $this->assertFalse($date->between(new DateTimeImmutable('2000-01-15 00:00:00'), new DateTimeImmutable('2000-01-14 00:00:00'), false));
    }

    public function testMinIsFluid()
    {
        $dt = Chronos::now();
        $this->assertTrue($dt->min() instanceof Chronos);
    }

    public function testMinWithNow()
    {
        $dt = Chronos::create(2012, 1, 1, 0, 0, 0)->min();
        $this->assertDateTime($dt, 2012, 1, 1, 0, 0, 0);
    }

    public function testMinWithInstance()
    {
        $dt1 = Chronos::create(2013, 12, 31, 23, 59, 59);
        $dt2 = Chronos::create(2012, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertDateTime($dt2, 2012, 1, 1, 0, 0, 0);

        $dt1 = new DateTimeImmutable('2013-12-31 23:59:59');
        $dt2 = Chronos::create(2012, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertDateTime($dt2, 2012, 1, 1, 0, 0, 0);
    }

    public function testMaxIsFluid()
    {
        $dt = Chronos::now();
        $this->assertTrue($dt->max() instanceof Chronos);
    }

    public function testMaxWithNow()
    {
        $dt = Chronos::create(2099, 12, 31, 23, 59, 59)->max();
        $this->assertDateTime($dt, 2099, 12, 31, 23, 59, 59);
    }

    public function testMaxWithInstance()
    {
        $dt1 = Chronos::create(2012, 1, 1, 0, 0, 0);
        $dt2 = Chronos::create(2099, 12, 31, 23, 59, 59)->max($dt1);
        $this->assertDateTime($dt2, 2099, 12, 31, 23, 59, 59);

        $dt1 = new DateTimeImmutable('2012-01-01 00:00:00');
        $dt2 = Chronos::create(2099, 12, 31, 23, 59, 59)->max($dt1);
        $this->assertDateTime($dt2, 2099, 12, 31, 23, 59, 59);
    }

    public function testIsBirthday()
    {
        $dt = Chronos::now();
        $aBirthday = $dt->subYears(1);
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDays(1);
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = Chronos::createFromDate(1987, 4, 23);
        $dt2 = Chronos::createFromDate(2014, 9, 26);
        $dt3 = Chronos::createFromDate(2014, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));

        $this->assertTrue($dt1->isBirthday(new DateTimeImmutable('2014-04-23 00:00:00')));
        $this->assertFalse($dt1->isBirthday(new DateTimeImmutable('2014-04-22 00:00:00')));
    }

    public function testClosest()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);

        $dt1 = new DateTimeImmutable('2015-05-28 11:00:00');
        $dt2 = new DateTimeImmutable('2015-05-28 14:00:00');
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
        $this->assertInstanceOf(Chronos::class, $closest);
    }

    public function testClosestWithEquals()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertSame($dt1, $closest);

        $dt1 = new DateTimeImmutable('2015-05-28 11:00:00');
        $dt2 = new DateTimeImmutable('2015-05-28 14:00:00');
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
        $this->assertInstanceOf(Chronos::class, $closest);
    }

    public function testClosestWithOthers(): void
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $dt3 = Chronos::create(2015, 5, 28, 15, 0, 0);
        $dt4 = Chronos::create(2015, 5, 28, 16, 0, 0);
        $closest = $instance->closest($dt4, $dt3, $dt1, $dt2);
        $this->assertSame($dt1, $closest);
    }

    public function testFarthest()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);

        $dt1 = new DateTimeImmutable('2015-05-28 11:00:00');
        $dt2 = new DateTimeImmutable('2015-05-28 14:00:00');
        $farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $farthest);
        $this->assertInstanceOf(Chronos::class, $farthest);
    }

    public function testFarthestWithEquals()
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertSame($dt2, $Farthest);

        $dt1 = new DateTimeImmutable('2015-05-28 12:00:00');
        $dt2 = new DateTimeImmutable('2015-05-28 14:00:00');
        $farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $farthest);
        $this->assertInstanceOf(Chronos::class, $farthest);
    }

    public function testFarthestWithOthers(): void
    {
        $instance = Chronos::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Chronos::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Chronos::create(2015, 5, 28, 14, 0, 0);
        $dt3 = Chronos::create(2015, 5, 28, 15, 0, 0);
        $dt4 = Chronos::create(2015, 5, 28, 16, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2, $dt3, $dt4);
        $this->assertSame($dt4, $Farthest);
    }
}
