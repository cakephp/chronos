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

namespace Cake\Chronos;

use DateInterval;
use DatePeriod;
use InvalidArgumentException;
use Iterator;

/**
 * DatePeriod wrapper that returns Chronos instances.
 *
 * @template TKey int
 * @template TValue \Cake\Chronos\Chronos
 * @template-implements \Iterator<int, \Cake\Chronos\Chronos>
 */
class ChronosPeriod implements Iterator
{
    /**
     * @var \Iterator<int, \DateTimeInterface>
     */
    protected Iterator $iterator;

    /**
     * @param \DatePeriod $period The DatePeriod to wrap.
     * @throws \InvalidArgumentException If the period has a zero interval which would cause an infinite loop.
     */
    public function __construct(DatePeriod $period)
    {
        if (static::isZeroInterval($period->getDateInterval())) {
            throw new InvalidArgumentException(
                'Cannot create a period with a zero interval. This would cause an infinite loop when iterating.',
            );
        }

        /** @var \Iterator<int, \DateTimeInterface> $iterator */
        $iterator = $period->getIterator();
        $this->iterator = $iterator;
    }

    /**
     * Check if a DateInterval is effectively zero.
     *
     * @param \DateInterval $interval The interval to check.
     * @return bool True if the interval is zero.
     */
    protected static function isZeroInterval(DateInterval $interval): bool
    {
        return $interval->y === 0
            && $interval->m === 0
            && $interval->d === 0
            && $interval->h === 0
            && $interval->i === 0
            && $interval->s === 0
            && (int)($interval->f * 1_000_000) === 0;
    }

    /**
     * @return \Cake\Chronos\Chronos
     */
    public function current(): Chronos
    {
        return new Chronos($this->iterator->current());
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->iterator->key();
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->iterator->valid();
    }
}
