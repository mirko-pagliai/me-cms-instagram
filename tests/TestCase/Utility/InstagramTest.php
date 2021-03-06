<?php
declare(strict_types=1);

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
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
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
     * Called before every test method
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Instagram = new Instagram();
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        Cache::clear('instagram');
    }

    /**
     * Internal method to get a mock instance of `InstagramComponent`
     */
    protected function getInstagramComponentMock(): object
    {
        $methods = [
            'getMediaResponse' => 'media.json',
            'getRecentResponse' => 'recent.json',
            'getUserResponse' => 'user.json',
        ];

        $instance = $this->getMockBuilder(Instagram::class)
            ->setMethods(array_keys($methods))
            ->getMock();
        foreach ($methods as $method => $value) {
            $content = file_get_contents(TEST_APP . 'examples' . DS . $value);
            $instance->method($method)->will($this->returnValue($content));
        }

        return $instance;
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $this->assertEquals(getConfigOrFail('Instagram.key'), $this->getProperty($this->Instagram, 'key'));

        $Instagram = new Instagram('anotherKey');
        $this->assertEquals('anotherKey', $this->getProperty($Instagram, 'key'));
    }

    /**
     * Test for `media()` method
     * @test
     */
    public function testMedia()
    {
        $result = $this->getInstagramComponentMock()->media('1');
        $this->assertInstanceof(Entity::class, $result);
        $this->assertEquals([
            'id' => 1,
            'path' => 'https://github.com/mirko-pagliai/me-cms-instagram/blob/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKeyStandard',
            'filename' => '1.png',
        ], $this->getInstagramComponentMock()->media('1')->toArray());

        //With no media data
        $this->expectException(NotFoundException::class);
        $this->Instagram->media('1');
    }

    /**
     * Test for `recent()` method
     * @test
     */
    public function testRecent()
    {
        [$photos, $nextId] = $this->getInstagramComponentMock()->recent();

        $this->assertEquals('111_222', $nextId);
        $this->assertEquals(12, count($photos));

        $i = 0;

        //Asserts for each photo
        foreach ($photos as $photo) {
            $this->assertInstanceOf(Entity::class, $photo);
            $this->assertEquals([
                'id' => '9999999999999999999_999999' . sprintf('%02d', ++$i),
                'description' => 'Example text ' . $i,
                'link' => 'http://example/link' . $i . '/',
                'path' => 'https://raw.githubusercontent.com/mirko-pagliai/me-cms-instagram/develop/tests/test_app/examples/1.png?ig_cache_key=cacheKey' . $i . 'Standard',
                'filename' => '1.png',
            ], $photo->toArray());
        }

        //With no recent data
        $this->expectException(NotFoundException::class);
        $this->Instagram->recent('1');
    }

    /**
     * Test for `user()` method
     * @test
     */
    public function testUser()
    {
        $result = $this->getInstagramComponentMock()->user();
        $this->assertInstanceof(Entity::class, $result);
        $this->assertInstanceof(Entity::class, $result->counts);
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

        //With no user data
        $this->expectException(NotFoundException::class);
        $this->Instagram->user();
    }
}
