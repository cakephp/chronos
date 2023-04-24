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
namespace Cake\Chronos\Test\Benchmark;

use Cake\Chronos\Chronos;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"shutdown"})
 */
class DiffBench
{
    public function init()
    {
        $this->from = new Chronos('2019-01-01 00:00:00');
        $this->to = new Chronos('2020-01-01 00:00:00');
    }

    public function shutdown()
    {
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDiffYears()
    {
        $this->from->diffInYears($this->to);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDiffMonths()
    {
        $this->from->diffInMonths($this->to);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDiffDays()
    {
        $this->from->diffInDays($this->to);
    }
}
