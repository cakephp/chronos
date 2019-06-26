<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice. Provides various operator methods for datetime
 * objects.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Traits;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

/**
 * Provides methods for setting a 'test' now. This lets you
 * retrieve pre-determined times with now().
 */
trait TestingAidTrait
{

    /**
     * Set the test now used by Date and Time classes provided by Chronos
     *
     * @see \Cake\Chronos\Chronos::setTestNow()
     * @param \Cake\Chronos\ChronosInterface|string|null $testNow The instance to use for all future instances.
     * @return void
     */
    public static function setTestNow($testNow = null)
    {
        Chronos::setTestNow($testNow);
    }

    /**
     * Get the test instance stored in Chronos
     *
     * @see \Cake\Chronos\Chronos::getTestNow()
     * @return \Cake\Chronos\ChronosInterface|null the current instance used for testing or null.
     */
    public static function getTestNow()
    {
        return Chronos::getTestNow();
    }

    /**
     * Get whether or not Chronos has a test instance set.
     *
     * @see \Cake\Chronos\Chronos::hasTestNow()
     * @return bool True if there is a test instance, otherwise false
     */
    public static function hasTestNow()
    {
        return Chronos::hasTestNow();
    }
}
