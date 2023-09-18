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
        $this->assertSame($expectedHalfOfYear, $d->halfOfYear);
    }
}
