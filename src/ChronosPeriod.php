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

use DatePeriod;
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
     * @param \DatePeriod $period
     */
    public function __construct(DatePeriod $period)
    {
        /** @var \Iterator<int, \DateTimeInterface> $iterator */
        $iterator = $period->getIterator();
        $this->iterator = $iterator;
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
