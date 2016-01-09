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

namespace Cake\Chronos\Test\DateTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\DateTime;
use TestCase;

class ChronosTest extends TestCase
{
    /**
     * @return void
     */
    public function testChronosAliasInstance()
    {
        $d = new Chronos();
        $this->assertTrue($d instanceof DateTime);
    }
}
