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
use Cake\Chronos\ChronosDate;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"shutdown"})
 */
class ConstructBench
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

    public function provideClasses()
    {
        yield 'chronos' => ['class' => Chronos::class];
        yield 'date' => ['class' => ChronosDate::class];
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchNow($params)
    {
        $class = $params['class'];
        $class::now();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchNowTimezone($params)
    {
        $class = $params['class'];
        $class::now('Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchRelative($params)
    {
        $class = $params['class'];
        $class::parse('+2 days');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchRelativeTimezone($params)
    {
        $class = $params['class'];
        $class::parse('+2 days', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFixed($params)
    {
        $class = $params['class'];
        $class::parse('2001-01-01 01:02:03.123456');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFixedTimezone($params)
    {
        $class = $params['class'];
        $class::parse('2001-01-01 01:02:03.123456', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchCreate($params)
    {
        $class = $params['class'];
        $class::create(2001, 01, 01, 01, 02, 03);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchCreateTimezone($params)
    {
        $class = $params['class'];
        $class::create(2001, 01, 01, 01, 02, 03, 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromFormat($params)
    {
        $class = $params['class'];
        $class::createFromFormat('Y-m-d H:i:s.u', '2001-01-01 01:02:03.123456');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromFormatTimezone($params)
    {
        $class = $params['class'];
        $class::createFromFormat('Y-m-d H:i:s.u', '2001-01-01 01:02:03.123456', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromTimestamp($params)
    {
        $class = $params['class'];
        $class::createFromTimestamp(1454284800);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromTimestampUTC($params)
    {
        $class = $params['class'];
        $class::createFromTimestamp(1454284800);
    }
}
