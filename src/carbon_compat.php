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

// Check if the interface alias exists and don't redeclare it in case we are in
// a preloaded context.
if (!\class_exists('Carbon\Carbon') && !\interface_exists('Carbon\CarbonInterface', false)) {
    // Create class aliases for Carbon so applications
    // can upgrade more easily.
    class_alias('Cake\Chronos\Chronos', 'Carbon\MutableDateTime');
    class_alias('Cake\Chronos\ChronosInterface', 'Carbon\CarbonInterface');
}
