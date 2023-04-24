<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos;

/**
 * Handles formatting differences in text.
 *
 * Provides a swappable component for other libraries to leverage.
 * when localizing or customizing the difference output.
 */
class DifferenceFormatter implements DifferenceFormatterInterface
{
    /**
     * The text translator object
     *
     * @var \Cake\Chronos\Translator
     */
    protected $translate;

    /**
     * Constructor.
     *
     * @param \Cake\Chronos\Translator|null $translate The text translator object.
     */
    public function __construct(?Translator $translate = null)
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
    public function diffForHumans(
        ChronosInterface $date,
        ?ChronosInterface $other = null,
        bool $absolute = false
    ): string {
        $isNow = $other === null;
        if ($isNow) {
            $other = $date->now($date->tz);
        }
        $diffInterval = $date->diff($other);

        switch (true) {
            case $diffInterval->y > 0:
                $unit = 'year';
                $count = $diffInterval->y;
                break;
            case $diffInterval->m >= 2:
                $unit = 'month';
                $count = $diffInterval->m;
                break;
            case $diffInterval->days >= ChronosInterface::DAYS_PER_WEEK * 3:
                $unit = 'week';
                $count = (int)($diffInterval->days / ChronosInterface::DAYS_PER_WEEK);
                break;
            case $diffInterval->d > 0:
                $unit = 'day';
                $count = $diffInterval->d;
                break;
            case $diffInterval->h > 0:
                $unit = 'hour';
                $count = $diffInterval->h;
                break;
            case $diffInterval->i > 0:
                $unit = 'minute';
                $count = $diffInterval->i;
                break;
            default:
                $count = $diffInterval->s;
                $unit = 'second';
                break;
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
