<?php
declare(strict_types=1);

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\Test\TestCase\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class GettersTest extends TestCase
{
    #[TestWith([1, 1])]
    #[TestWith([2, 1])]
    #[TestWith([3, 1])]
    #[TestWith([4, 1])]
    #[TestWith([5, 1])]
    #[TestWith([6, 1])]
    #[TestWith([7, 2])]
    #[TestWith([8, 2])]
    #[TestWith([9, 2])]
    #[TestWith([10, 2])]
    #[TestWith([11, 2])]
    #[TestWith([12, 2])]
    public function testHalfOfYear(int $month, int $expectedHalfOfYear): void
    {
        $d = ChronosDate::create(year: 2012, month: $month, day: 1);
        $this->assertSame($expectedHalfOfYear, $d->half);
    }

    public function testToArray(): void
    {
        $d = ChronosDate::create(2024, 1, 15);
        $array = $d->toArray();

        $this->assertSame(2024, $array['year']);
        $this->assertSame(1, $array['month']);
        $this->assertSame(15, $array['day']);
        $this->assertCount(3, $array);
    }
}
