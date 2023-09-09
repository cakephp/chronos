<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Test constructors for Date objects.
 */
class ConstructTest extends TestCase
{
    public function testWithFancyString()
    {
        $c = new ChronosDate('first day of January 2008');
        $this->assertDate($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithFancyString()
    {
        $c = ChronosDate::parse('first day of January 2008');
        $this->assertDate($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithMicroSeconds()
    {
        $date = ChronosDate::parse('2016-12-08 18:06:46.510954');
        $this->assertNotNull($date);
    }

    /**
     * Data provider for constructor testing.
     *
     * @return array
     */
    public static function inputTimeProvider()
    {
        return [
            ['@' . strtotime('2015-08-19 22:24:32')],
            ['2015-08-19 10:00:00'],
            ['2015-08-19T10:00:00+05:00'],
            ['Monday, 15-Aug-2005 15:52:01 UTC'],
            ['Mon, 15 Aug 05 15:52:01 +0000'],
            ['Monday, 15-Aug-05 15:52:01 UTC'],
            ['Mon, 15 Aug 05 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['Mon, 15 Aug 2005 15:52:01 +0000'],
            ['2005-08-15T15:52:01+00:00'],
            ['20050815'],
        ];
    }

    /**
     * @dataProvider inputTimeProvider
     * @return void
     */
    public function testConstructWithTimeParts($time)
    {
        $date = new ChronosDate($time);
        $this->assertNotNull($date);
    }

    public function testConstructWithTestNow()
    {
        Chronos::setTestNow(Chronos::create(2001, 1, 1));
        $date = new ChronosDate('+2 days');
        $this->assertDate($date, 2001, 1, 3);

        $date = new ChronosDate('2015-12-12');
        $this->assertDate($date, 2015, 12, 12);
    }

    public function testConstructWithRelative()
    {
        $c = new ChronosDate('+7 days');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new ChronosDate('+10 minutes');
        $this->assertSame('00:00:00', $c->format('H:i:s'));

        $c = new ChronosDate('2001-01-01 +7 days');
        $this->assertSame('2001-01-08', $c->format('Y-m-d'));
    }

    public function testConstructWithLocalTimezone(): void
    {
        $londonTimezone = new DateTimeZone('Europe/London');

        // now adjusted to London time
        // This test could have different results depending on when now is
        $c = new ChronosDate('now', $londonTimezone);
        $london = new DateTimeImmutable('now', $londonTimezone);
        $this->assertSame($london->format('Y-m-d'), $c->format('Y-m-d'));

        // now adjusted to London time
        $c = ChronosDate::today($londonTimezone);
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // London timezone is used instead of local timezone
        $c = new ChronosDate('2001-01-02 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-02 00:00:00', $c->format('Y-m-d H:i:s'));

        // London timezone is ignored when timezone is provided in time string
        $c = new ChronosDate('2001-01-01 23:00:00-400', $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));

        // London timezone is ignored when DateTimeInterface instance is provided
        $c = new ChronosDate(new DateTimeImmutable('2001-01-01 23:00:00-400'), $londonTimezone);
        $this->assertSame('2001-01-01 00:00:00', $c->format('Y-m-d H:i:s'));
    }

    public function testConstructWithLocalTimezoneTestNow(): void
    {
        Chronos::setTestNow(new Chronos('2010-01-01 23:00:00'));

        $londonTimezone = new DateTimeZone('Europe/London');

        // TestNow is adjusted to London time
        $c = new ChronosDate('now', $londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));

        // TestNow is adjusted to London time
        $c = new ChronosDate('+2 days', $londonTimezone);
        $this->assertSame('2010-01-04 00:00:00', $c->format('Y-m-d H:i:s'));

        // TestNow is adjusted to London time
        $c = ChronosDate::today($londonTimezone);
        $this->assertSame('2010-01-02 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::today($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // TestNow is adjusted to London time
        $c = ChronosDate::tomorrow($londonTimezone);
        $this->assertSame('2010-01-03 00:00:00', $c->format('Y-m-d H:i:s'));
        $this->assertSame(Chronos::tomorrow($londonTimezone)->format('Y-m-d'), $c->format('Y-m-d'));

        // TestNow is ignored when specific date is provided
        $c = new ChronosDate('2001-01-05 01:00:00', $londonTimezone);
        $this->assertSame('2001-01-05 00:00:00', $c->format('Y-m-d H:i:s'));
    }

    /**
     * This tests with a large difference between local timezone and
     * timezone provided as parameter.  This is to help guarantee a date
     * change would occur so the tests are more consistent.
     */
    public function testConstructWithLargeTimezoneChange(): void
    {
        date_default_timezone_set('Pacific/Kiritimati');

        $samoaTimezone = new DateTimeZone('Pacific/Samoa');

        // Pacific/Samoa -11:00 is used intead of local timezone +14:00
        $c = ChronosDate::today($samoaTimezone);
        $samoa = new DateTimeImmutable('now', $samoaTimezone);
        $this->assertSame($samoa->format('Y-m-d'), $c->format('Y-m-d'));
    }

    public function testCreateFromExistingInstance()
    {
        $existingClass = new ChronosDate(new Chronos());
        $this->assertInstanceOf(ChronosDate::class, $existingClass);

        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);

        $this->assertSame((string)$existingClass, (string)$newClass);
    }

    public function testCreateFromChronos()
    {
        $chronos = new Chronos('2021-01-01 01:01:01');
        $date = new ChronosDate($chronos);
        $this->assertSame('2021-01-01 00:00:00', $date->format('Y-m-d H:i:s'));
    }

    public function testCreateFromDateTimeInterface()
    {
        $existingClass = new DateTimeImmutable();
        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));

        $existingClass = new DateTime();
        $newClass = new ChronosDate($existingClass);
        $this->assertInstanceOf(ChronosDate::class, $newClass);
        $this->assertSame($existingClass->format('Y-m-d 00:00:00'), $newClass->format('Y-m-d H:i:s'));
    }

    public function testCreateFromFormat()
    {
        $date = ChronosDate::createFromFormat('Y-m-d P', '2014-02-01 Asia/Tokyo');
        $this->assertSame('2014-02-01 00:00:00 America/Toronto', $date->format('Y-m-d H:i:s e'));
    }

    public function testCreateFromFormatInvalidFormat()
    {
        $parseException = null;
        try {
            ChronosDate::createFromFormat('Y-m-d', '1975-05');
        } catch (InvalidArgumentException $e) {
            $parseException = $e;
        }

        $this->assertNotNull($parseException);
        $this->assertIsArray(ChronosDate::getLastErrors());
        $this->assertNotEmpty(ChronosDate::getLastErrors()['errors']);
    }
}
