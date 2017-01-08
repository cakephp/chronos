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

/**
 * Handles formatting differences in text.
 *
 * Provides a swappable component for other libraries to leverage.
 * when localizing or customizing the difference output.
 */
class DifferenceFormatter
{
    /**
     * Constructor.
     *
     * @param \Cake\Chronos\Translator|null $translate The text translator object.
     */
    public function __construct($translate = null)
    {
        $this->translate = $translate ?: new Translator();
    }

    /**
     * Get the difference in a human readable format.
     *
     * @param \Cake\Chronos\ChronosInterface $date The datetime to start with.
     * @param \Cake\Chronos\ChronosInterface|null $other The datetime to compare against.
     * @param bool $absolute removes time difference modifiers ago, after, etc
     * @return string The difference between the two days in a human readable format
     * @see \Cake\Chronos\ChronosInterface::diffForHumans
     */
    public function diffForHumans(ChronosInterface $date, ChronosInterface $other = null, $absolute = false)
    {
        $isNow = $other === null;
        if ($isNow) {
            $other = $date->now($date->tz);
        }
        $diffInterval = $date->diff($other);

        switch (true) {
            case ($diffInterval->y > 0):
                $unit = 'year';
                $count = $diffInterval->y;
                break;
            case ($diffInterval->m > 0):
                $unit = 'month';
                $count = $diffInterval->m;
                break;
            case ($diffInterval->d > 0):
                $unit = 'day';
                $count = $diffInterval->d;
                if ($count >= ChronosInterface::DAYS_PER_WEEK) {
                    $unit = 'week';
                    $count = (int)($count / ChronosInterface::DAYS_PER_WEEK);
                }
                break;
            case ($diffInterval->h > 0):
                $unit = 'hour';
                $count = $diffInterval->h;
                break;
            case ($diffInterval->i > 0):
                $unit = 'minute';
                $count = $diffInterval->i;
                break;
            default:
                $count = $diffInterval->s;
                $unit = 'second';
                break;
        }
        if ($count === 0) {
            $count = 1;
        }
        $time = $this->translate->plural($unit, $count, ['count' => $count]);
        if ($absolute) {
            return $time;
        }
        $isFuture = $diffInterval->invert === 1;
        $transId = $isNow ? ($isFuture ? 'from_now' : 'ago') : ($isFuture ? 'after' : 'before');

        // Some langs have special pluralization for past and future tense.
        $tryKeyExists = $unit . '_' . $transId;
        if ($this->translate->exists($tryKeyExists)) {
            $time = $this->translate->plural($tryKeyExists, $count, ['count' => $count]);
        }

        return $this->translate->singular($transId, ['time' => $time]);
    }
}
