<?php
namespace Cake\Chronos\Benchmarks\Chronos;

use Cake\Chronos\Chronos;

class SetDateBench
{
    /**
     * @Revs(100000)
     * @Iterations(5)
     * @return void
     */
    public function benchSetDate()
    {
        $date = (new Chronos())->setDate(1, 1, 1);
    }
}
