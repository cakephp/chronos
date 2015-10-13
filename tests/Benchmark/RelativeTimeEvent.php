<?php
namespace Cake\Chronos\Test\Benchmark;

use Athletic\AthleticEvent;
use Cake\Chronos\Chronos;

/**
 * Benchmark relative time parsing.
 */
class RelativeTimeEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function hasRelativeKeywordPlus()
    {
        Chronos::hasRelativeKeywords('+3 days');
    }

    /**
     * @iterations 1000
     */
    public function hasRelativeKeywordWords()
    {
        Chronos::hasRelativeKeywords('first day of month');
    }
}
