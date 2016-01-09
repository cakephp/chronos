<?php
namespace Cake\Chronos\Test\Benchmark;

use Athletic\AthleticEvent;
use Cake\Chronos\DateTime;

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
        DateTime::hasRelativeKeywords('+3 days');
    }

    /**
     * @iterations 1000
     */
    public function hasRelativeKeywordWords()
    {
        DateTime::hasRelativeKeywords('first day of month');
    }
}
