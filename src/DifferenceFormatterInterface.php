<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

/**
 * Interface for formatting differences in text.
 */
interface DifferenceFormatterInterface
{
    /**
     * Get the difference in a human readable format.
     *
     * @param \Cake\Chronos\ChronosInterface $date The datetime to start with.
     * @param \Cake\Chronos\ChronosInterface|null $other The datetime to compare against.
     * @param bool $absolute Removes time difference modifiers ago, after, etc.
     * @return string The difference between the two days in a human readable format.
     * @see \Cake\Chronos\ChronosInterface::diffForHumans
     */
    public function diffForHumans(
        ChronosInterface $date,
        ?ChronosInterface $other = null,
        bool $absolute = false
    ): string;
}
