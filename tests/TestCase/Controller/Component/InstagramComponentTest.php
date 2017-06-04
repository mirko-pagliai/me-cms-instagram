<?php
/**
 * This file is part of me-cms-instagram.
 *
 * me-cms-instagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * me-cms-instagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with me-cms-instagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeCmsInstagram\Test\TestCase\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use MeCmsInstagram\Controller\Component\InstagramComponent;

/**
 * InstagramComponentTest class
 */
class InstagramComponentTest extends TestCase
{
    /**
     * @var \MeCmsInstagram\Controller\Component\InstagramComponent
     */
    protected $Instagram;

    /**
     * Internal method to get a mock instance of `InstagramComponent`
     */
    protected function getInstagramComponentMock()
    {
        $component = $this->getMockBuilder(InstagramComponent::class)
            ->setConstructorArgs([new ComponentRegistry])
            ->setMethods(['_getMediaResponse', '_getRecentResponse', '_getUserResponse'])
            ->getMock();

        $component->method('_getMediaResponse')
            ->will($this->returnCallback(function () {
                return file_get_contents(TEST_APP . 'examples' . DS . 'media.json');
            }));

        $component->method('_getRecentResponse')
            ->will($this->returnCallback(function () {
                return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
            }));

        $component->method('_getUserResponse')
            ->will($this->returnCallback(function () {
                return file_get_contents(TEST_APP . 'examples' . DS . 'user.json');
            }));

        return $component;
    }

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Cache::clearAll();

        $this->Instagram = new InstagramComponent(new ComponentRegistry);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Instagram);
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $this->assertInstanceof('Cake\Http\Client', $this->Instagram->Client);
        $this->assertEquals(config('Instagram.key'), $this->Instagram->key);

        $this->Instagram = new InstagramComponent(new ComponentRegistry, ['key' => 'anotherKey']);
        $this->assertEquals('anotherKey', $this->Instagram->key);
    }

    /**
     * Test for `media()` method
     * @test
     */
    public function testMedia()
    {
        $expected = (object)[
            'id' => 1,
            'path' => 'https://github.com/mirko-pagliai/me-cms-instagram/blob/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKeyStandard',
            'filename' => '1.png',
        ];

        $this->assertEquals($expected, $this->getInstagramComponentMock()->media(1));
    }

    /**
     * Test for `media()` method, with no media data
     * @expectedException Cake\Network\Exception\NotFoundException
     * @expectedExceptionMessage Record not found
     * @test
     */
    public function testUserNoMediaData()
    {
        $this->Instagram->media(1);
    }

    /**
     * Test for `recent()` method
     * @test
     */
    public function testRecent()
    {
        list($photos, $nextId) = $this->getInstagramComponentMock()->recent();

        $this->assertEquals('111_222', $nextId);
        $this->assertEquals(12, count($photos));

        $i = 0;

        //Asserts for each photo
        foreach ($photos as $photo) {
            ++$i;

            $expected = [
                'id' => '9999999999999999999_999999' . sprintf('%02d', $i),
                'description' => 'Example text ' . $i,
                'link' => 'http://example/link' . $i . '/',
                'path' => 'https://raw.githubusercontent.com/mirko-pagliai/me-cms-instagram/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKey' . $i . 'Standard',
                'filename' => '1.png',
            ];

            $this->assertInstanceOf('stdClass', $photo);
            $this->assertEquals($expected, (array)$photo);
        }
    }

    /**
     * Test for `recent()` method, with no recent data
     * @expectedException Cake\Network\Exception\NotFoundException
     * @expectedExceptionMessage Record not found
     * @test
     */
    public function testUserNoRecentData()
    {
        $this->Instagram->recent(1);
    }
    /**
     * Test for `user()` method
     * @test
     */
    public function testUser()
    {
        $expected = (object)[
            'username' => 'myusername',
            'bio' => 'mybio',
            'website' => 'http://example/site',
            'profile_picture' => 'http://example/image.jpg',
            'full_name' => 'Full Name',
            'counts' => (object)[
                'media' => 148,
                'followed_by' => 569,
                'follows' => 185,
            ],
            'id' => '99999999',
        ];

        $this->assertEquals($expected, $this->getInstagramComponentMock()->user());
    }

    /**
     * Test for `user()` method, with no user data
     * @expectedException Cake\Network\Exception\NotFoundException
     * @expectedExceptionMessage Record not found
     * @test
     */
    public function testUserNoUserData()
    {
        $this->Instagram->user();
    }
}
