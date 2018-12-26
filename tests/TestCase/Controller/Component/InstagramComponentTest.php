<?php
/**
 * This file is part of me-cms-instagram.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-instagram
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace MeCmsInstagram\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use MeCmsInstagram\Controller\Component\InstagramComponent;
use MeTools\TestSuite\ComponentTestCase;

/**
 * InstagramComponentTest class
 */
class InstagramComponentTest extends ComponentTestCase
{
    /**
     * Test for `initialize()` method
     * @test
     */
    public function testInitialize()
    {
        $this->assertEquals(getConfigOrFail('Instagram.key'), $this->Component->key);

        $component = new InstagramComponent(new ComponentRegistry, ['key' => 'anotherKey']);
        $this->assertEquals('anotherKey', $component->key);
    }
}
