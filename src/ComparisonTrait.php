<?php
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
namespace Cake\Chronos;

use Cake\Chronos\ChronosInterface;

/**
 * Provides various comparison operator methods for datetime objects.
 */
trait ComparisonTrait
{
    /**
     * Determines if the instance is equal to another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function eq(ChronosInterface $dt)
    {
        return $this == $dt;
    }

    /**
     * Determines if the instance is not equal to another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function ne(ChronosInterface $dt)
    {
        return !$this->eq($dt);
    }

    /**
     * Determines if the instance is greater (after) than another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function gt(ChronosInterface $dt)
    {
        return $this > $dt;
    }

    /**
     * Determines if the instance is greater (after) than or equal to another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function gte(ChronosInterface $dt)
    {
        return $this >= $dt;
    }

    /**
     * Determines if the instance is less (before) than another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lt(ChronosInterface $dt)
    {
        return $this < $dt;
    }

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return bool
     */
    public function lte(ChronosInterface $dt)
    {
        return $this <= $dt;
    }

    /**
     * Determines if the instance is between two others
     *
     * @param ChronosInterface $dt1 The instance to compare with.
     * @param ChronosInterface $dt2 The instance to compare with.
     * @param bool $equal Indicates if a > and < comparison should be used or <= or >=
     * @return bool
     */
    public function between(ChronosInterface $dt1, ChronosInterface $dt2, $equal = true)
    {
        if ($dt1->gt($dt2)) {
            $temp = $dt1;
            $dt1 = $dt2;
            $dt2 = $temp;
        }

        if ($equal) {
            return $this->gte($dt1) && $this->lte($dt2);
        } else {
            return $this->gt($dt1) && $this->lt($dt2);
        }
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function min(ChronosInterface $dt = null)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;

        return $this->lt($dt) ? $this : $dt;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param ChronosInterface $dt The instance to compare with.
     * @return static
     */
    public function max(ChronosInterface $dt = null)
    {
        $dt = ($dt === null) ? static::now($this->tz) : $dt;

        return $this->gt($dt) ? $this : $dt;
    }
}
