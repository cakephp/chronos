<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice. Provides various operator methods for datetime
 * objects.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Traits;

/**
 * Provides methods for testing if strings contain relative keywords.
 */
trait RelativeKeywordTrait
{
    /**
     * Regex for relative period.
     *
     * @var string
     */
    protected static $relativePattern = '/this|next|last|tomorrow|yesterday|midnight|today|[+-]|first|last|ago/i';

    /**
     * Determine if there is just a time in the time string
     *
     * @param string $time The time string to check.
     * @return bool true if there is a keyword, otherwise false
     */
    private static function isTimeExpression($time)
    {
        // Just a time
        if (is_string($time) && preg_match('/^[0-2]?[0-9]:[0-5][0-9](?::[0-5][0-9](?:\.[0-9]{1,6})?)?$/', $time)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if there is a relative keyword in the time string, this is to
     * create dates relative to now for test instances. e.g.: next tuesday
     *
     * @param string|null $time The time string to check.
     * @return bool true if there is a keyword, otherwise false
     */
    public static function hasRelativeKeywords(?string $time): bool
    {
        if (self::isTimeExpression($time)) {
            return true;
        }
        // skip common format with a '-' in it
        if ($time && preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1) {
            return preg_match(static::$relativePattern, $time) > 0;
        }

        return false;
    }

    /**
     * Determines if there is no fixed date in the time string.
     *
     * @param \DateTimeInterface|string|null $time The time string to check
     * @return bool true if doesn't contain a fixed date
     */
    private static function isRelativeOnly($time): bool
    {
        if ($time === null) {
            return true;
        }

        if (!is_string($time)) {
            return false;
        }

        // must not contain fixed date before relative keywords or time expression
        return preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1;
    }
}
