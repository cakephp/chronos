<?php
namespace Cake\Chronos\Benchmarks\MutableDateTime;

use Cake\Chronos\MutableDateTime;

class SetDateBench
{
    /**
     * @Revs(100000)
     * @Iterations(5)
     * @return void
     */
    public function benchSetDate()
    {
        $date = (new MutableDateTime())->setDate(1, 1, 1);
    }
}
