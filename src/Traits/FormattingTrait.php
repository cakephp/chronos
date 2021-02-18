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
namespace Cake\Chronos\Traits;

use Cake\Chronos\ChronosInterface;
use DateTime;

/**
 * Provides string formatting methods for datetime instances.
 *
 * Expects implementing classes to define static::$toStringFormat
 */
trait FormattingTrait
{
    /**
     * Reset the format used to the default when type juggling a ChronosInterface instance to a string
     *
     * @return void
     */
    public static function resetToStringFormat(): void
    {
        static::setToStringFormat(ChronosInterface::DEFAULT_TO_STRING_FORMAT);
    }

    /**
     * Set the default format used when type juggling a ChronosInterface instance to a string
     *
     * @param string $format The format to use in future __toString() calls.
     * @return void
     */
    public static function setToStringFormat($format): void
    {
        static::$toStringFormat = $format;
    }

    /**
     * Format the instance as a string using the set format
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format(static::$toStringFormat);
    }

    /**
     * Format the instance as date
     *
     * @return string
     */
    public function toDateString(): string
    {
        return $this->format('Y-m-d');
    }

    /**
     * Format the instance as a readable date
     *
     * @return string
     */
    public function toFormattedDateString(): string
    {
        return $this->format('M j, Y');
    }

    /**
     * Format the instance as time
     *
     * @return string
     */
    public function toTimeString(): string
    {
        return $this->format('H:i:s');
    }

    /**
     * Format the instance as date and time
     *
     * @return string
     */
    public function toDateTimeString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Format the instance with day, date and time
     *
     * @return string
     */
    public function toDayDateTimeString(): string
    {
        return $this->format('D, M j, Y g:i A');
    }

    /**
     * Format the instance as ATOM
     *
     * @return string
     */
    public function toAtomString(): string
    {
        return $this->format(DateTime::ATOM);
    }

    /**
     * Format the instance as COOKIE
     *
     * @return string
     */
    public function toCookieString(): string
    {
        return $this->format(DateTime::COOKIE);
    }

    /**
     * Format the instance as ISO8601
     *
     * @return string
     */
    public function toIso8601String(): string
    {
        return $this->format(DateTime::ATOM);
    }

    /**
     * Format the instance as RFC822
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc822
     */
    public function toRfc822String(): string
    {
        return $this->format(DateTime::RFC822);
    }

    /**
     * Format the instance as RFC850
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc850
     */
    public function toRfc850String(): string
    {
        return $this->format(DateTime::RFC850);
    }

    /**
     * Format the instance as RFC1036
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc1036
     */
    public function toRfc1036String(): string
    {
        return $this->format(DateTime::RFC1036);
    }

    /**
     * Format the instance as RFC1123
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc1123
     */
    public function toRfc1123String(): string
    {
        return $this->format(DateTime::RFC1123);
    }

    /**
     * Format the instance as RFC2822
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc2822
     */
    public function toRfc2822String(): string
    {
        return $this->format(DateTime::RFC2822);
    }

    /**
     * Format the instance as RFC3339
     *
     * @return string
     * @link https://tools.ietf.org/html/rfc3339
     */
    public function toRfc3339String(): string
    {
        return $this->format(DateTime::RFC3339);
    }

    /**
     * Format the instance as RSS
     *
     * @return string
     */
    public function toRssString(): string
    {
        return $this->format(DateTime::RSS);
    }

    /**
     * Format the instance as W3C
     *
     * @return string
     */
    public function toW3cString(): string
    {
        return $this->format(DateTime::W3C);
    }

    /**
     * Returns a UNIX timestamp.
     *
     * @return string UNIX timestamp
     */
    public function toUnixString(): string
    {
        return $this->format('U');
    }

    /**
     * Returns the quarter
     *
     * @param bool $range Range.
     * @return int|array 1, 2, 3, or 4 quarter of year or array if $range true
     */
    public function toQuarter(bool $range = false)
    {
        $quarter = (int)ceil($this->format('m') / 3);
        if ($range === false) {
            return $quarter;
        }

        $year = $this->format('Y');
        switch ($quarter) {
            case 1:
                return [$year . '-01-01', $year . '-03-31'];
            case 2:
                return [$year . '-04-01', $year . '-06-30'];
            case 3:
                return [$year . '-07-01', $year . '-09-30'];
            default:
                return [$year . '-10-01', $year . '-12-31'];
        }
    }

    /**
     * @inheritDoc
     */
    public function toWeek(): int
    {
        return (int)$this->format('W');
    }
}
