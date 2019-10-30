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
define('CHRONOS_SUPPORTS_MICROSECONDS', version_compare(PHP_VERSION, '7.1.0', '>='));

if (!class_exists('Carbon\Carbon')) {
    // Create class aliases for Carbon so applications
    // can upgrade more easily.
    class_alias('Cake\Chronos\Chronos', 'Carbon\MutableDateTime');
    class_alias('Cake\Chronos\ChronosInterface', 'Carbon\CarbonInterface');
}
