<?php
declare(strict_types=1);

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
namespace Cake\Chronos\Test\Benchmark;

use Cake\Chronos\Chronos;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"shutdown"})
 */
class ChronosConstructBench
{
    private $savedTz;

    public function init()
    {
        $this->savedTz = date_default_timezone_get();
        date_default_timezone_set('America/Toronto');
    }

    public function shutdown()
    {
        date_default_timezone_set($this->savedTz);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNow()
    {
        Chronos::now();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNowTimezone()
    {
        Chronos::now('Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRelative()
    {
        Chronos::parse('+2 days');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRelativeTimezone()
    {
        Chronos::parse('+2 days', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDateString()
    {
        Chronos::parse('2001-01-01 01:02:03.123456');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDateStringTimezone()
    {
        Chronos::parse('2001-01-01 01:02:03.123456', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDateCreate()
    {
        Chronos::create(2001, 01, 01, 01, 02, 03);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchDateCreateTimezone()
    {
        Chronos::create(2001, 01, 01, 01, 02, 03, 'Europe/London');
    }
}
