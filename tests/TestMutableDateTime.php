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
namespace Cake\Chronos\Test;

use \Cake\Chronos\MutableDateTime;

/**
 * Utility class for unit testing MutableDateTime
 *
 * @property-read string $date used in opis/closure serialization via reflection
 * @property-read int $timezone_type used in opis/closure serialization via reflection
 */
class TestMutableDateTime extends MutableDateTime
{
}