<?php
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
define('CHRONOS_SUPPORTS_MICROSECONDS', version_compare(PHP_VERSION, '7.1.0', '>='));

if (!class_exists('Carbon\Carbon')) {
    // Create class aliases for Carbon so applications
    // can upgrade more easily.
    class_alias('Cake\Chronos\Chronos', 'Carbon\MutableDateTime');
    class_alias('Cake\Chronos\ChronosInterface', 'Carbon\CarbonInterface');
}
