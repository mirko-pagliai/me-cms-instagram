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
namespace MeCmsInstagram\Test\TestCase\Utility;

use Cake\Cache\Cache;
use MeCmsInstagram\Utility\Instagram;
use MeTools\TestSuite\TestCase;

/**
 * InstagramTest class
 */
class InstagramTest extends TestCase
{
    /**
     * @var \MeCmsInstagram\Utility\Instagram
     */
    protected $Instagram;

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

        $this->Instagram = new Instagram;
    }

    /**
     * Internal method to get a mock instance of `InstagramComponent`
     */
    protected function getInstagramComponentMock()
    {
        $methods = [
            'getMediaResponse' => 'media.json',
            'getRecentResponse' => 'recent.json',
            'getUserResponse' => 'user.json',
        ];

        $instance = $this->getMockBuilder(Instagram::class)
            ->setMethods(array_keys($methods))
            ->getMock();

        foreach ($methods as $method => $filename) {
            $instance->method($method)
                ->will($this->returnValue(file_get_contents(TEST_APP . 'examples' . DS . $filename)));
        }

        return $instance;
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $this->assertEquals(getConfigOrFail('Instagram.key'), $this->invokeMethod($this->Instagram, 'getKey'));

        $key = 'anotherKey';

        $this->Instagram = new Instagram($key);
        $this->assertEquals($key, $this->invokeMethod($this->Instagram, 'getKey'));
    }

    /**
     * Test for `getClient()` method
     * @test
     */
    public function testGetClient()
    {
        $this->assertInstanceof('Cake\Http\Client', $this->invokeMethod($this->Instagram, 'getClient'));
    }

    /**
     * Test for `media()` method
     * @test
     */
    public function testMedia()
    {
        $result = $this->getInstagramComponentMock()->media(1);
        $this->assertInstanceof('Cake\ORM\Entity', $result);
        $this->assertEquals([
            'id' => 1,
            'path' => 'https://github.com/mirko-pagliai/me-cms-instagram/blob/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKeyStandard',
            'filename' => '1.png',
        ], $this->getInstagramComponentMock()->media(1)->toArray());
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

            $this->assertInstanceOf('Cake\ORM\Entity', $photo);
            $this->assertEquals([
                'id' => '9999999999999999999_999999' . sprintf('%02d', $i),
                'description' => 'Example text ' . $i,
                'link' => 'http://example/link' . $i . '/',
                'path' => 'https://raw.githubusercontent.com/mirko-pagliai/me-cms-instagram/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKey' . $i . 'Standard',
                'filename' => '1.png',
            ], $photo->toArray());
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
        $result = $this->getInstagramComponentMock()->user();
        $this->assertInstanceof('Cake\ORM\Entity', $result);
        $this->assertInstanceof('Cake\ORM\Entity', $result->counts);
        $this->assertEquals([
            'username' => 'myusername',
            'bio' => 'mybio',
            'website' => 'http://example/site',
            'profile_picture' => 'http://example/image.jpg',
            'full_name' => 'Full Name',
            'counts' => [
                'media' => 148,
                'followed_by' => 569,
                'follows' => 185,
            ],
            'id' => '99999999',
        ], $result->toArray());
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
