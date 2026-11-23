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
use Generator;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"shutdown"})
 */
class ConstructBench
{
    private ?string $savedTz = null;

    public function init(): void
    {
        $this->savedTz = date_default_timezone_get();
        date_default_timezone_set('America/Toronto');
    }

    public function shutdown(): void
    {
        date_default_timezone_set($this->savedTz);
    }

    public function provideClasses(): Generator
    {
        yield 'chronos' => ['class' => Chronos::class];
        yield 'date' => ['class' => ChronosDate::class];
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchNow(array $params): void
    {
        $class = $params['class'];
        $class::now();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchNowTimezone(array $params): void
    {
        $class = $params['class'];
        $class::now('Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchRelative(array $params): void
    {
        $class = $params['class'];
        $class::parse('+2 days');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchRelativeTimezone(array $params): void
    {
        $class = $params['class'];
        $class::parse('+2 days', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFixed(array $params): void
    {
        $class = $params['class'];
        $class::parse('2001-01-01 01:02:03.123456');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFixedTimezone(array $params): void
    {
        $class = $params['class'];
        $class::parse('2001-01-01 01:02:03.123456', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchCreate(array $params): void
    {
        $class = $params['class'];
        $class::create(2001, 01, 01, 01, 02, 03);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchCreateTimezone(array $params): void
    {
        $class = $params['class'];
        $class::create(2001, 01, 01, 01, 02, 03, 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromFormat(array $params): void
    {
        $class = $params['class'];
        $class::createFromFormat('Y-m-d H:i:s.u', '2001-01-01 01:02:03.123456');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromFormatTimezone(array $params): void
    {
        $class = $params['class'];
        $class::createFromFormat('Y-m-d H:i:s.u', '2001-01-01 01:02:03.123456', 'Europe/London');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromTimestamp(array $params): void
    {
        $class = $params['class'];
        $class::createFromTimestamp(1454284800);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @ParamProviders({"provideClasses"})
     */
    public function benchFromTimestampUTC(array $params): void
    {
        $class = $params['class'];
        $class::createFromTimestamp(1454284800);
    }
}
