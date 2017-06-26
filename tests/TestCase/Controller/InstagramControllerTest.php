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
namespace MeCmsInstagram\Test\TestCase\Controller;

use Cake\Cache\Cache;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\IntegrationTestCase;
use MeCmsInstagram\Controller\Component\InstagramComponent;

/**
 * InstagramControllerTest class
 */
class InstagramControllerTest extends IntegrationTestCase
{
    /**
     * @var \MeCmsInstagram\Controller\InstagramController
     */
    protected $InstagramComponent;

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

        $this->InstagramComponent = $this->getInstagramComponentMock();

        Cache::clearAll();
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->InstagramComponent);
    }

    /**
     * Adds additional event spies to the controller/view event manager
     * @param \Cake\Event\Event $event A dispatcher event
     * @param \Cake\Controller\Controller|null $controller Controller instance
     * @return void
     */
    public function controllerSpy($event, $controller = null)
    {
        //Mocks the `InstagramComponent`, expect for the testViewInvalidId` method
        if ($this->getName() !== 'testViewInvalidId') {
            $controller->Instagram = $this->getInstagramComponentMock();
        }

        $controller->viewBuilder()->setLayout(false);

        parent::controllerSpy($event, $controller);
    }

    /**
     * Test for `beforeRender()` method
     */
    public function testBeforeRender()
    {
        $this->get(['_name' => 'instagramPhotos']);

        $userFromView = $this->viewVariable('user');
        $this->assertInstanceof('stdClass', $userFromView);
        $this->assertNotEmpty($userFromView);

        $userFromCache = Cache::read('user_profile', 'instagram');
        $this->assertEquals($userFromView, $userFromCache);
    }

    /**
     * Test for `index()` method
     */
    public function testIndex()
    {
        $this->get(['_name' => 'instagramPhotos']);
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $this->assertTemplate(ROOT . 'src/Template/Instagram/index.ctp');

        $photosFromView = $this->viewVariable('photos');
        $this->assertTrue(is_array($photosFromView));
        $this->assertNotEmpty($photosFromView);

        foreach ($photosFromView as $photo) {
            $this->assertInstanceof('stdClass', $photo);
        }

        $nextIdFromView = $this->viewVariable('nextId');
        $this->assertEquals('111_222', $nextIdFromView);

        //Sets the cache name
        $cache = sprintf('index_limit_%s', getConfig('default.photos'));
        list($photosFromCache, $nextIdFromCache) = array_values(Cache::read($cache, 'instagram'));

        $this->assertEquals($photosFromView, $photosFromCache);
        $this->assertEquals($nextIdFromView, $nextIdFromCache);

        //GET request. Now with the `nextId`
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $this->assertTemplate(ROOT . 'src/Template/Instagram/index.ctp');

        //GET request. Now it's an ajax request, with the `nextId`
        $this->configRequest(['headers' => ['X-Requested-With' => 'XMLHttpRequest']]);
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $this->assertTemplate(ROOT . 'src/Template/Instagram/index.ctp');
        $this->assertLayout('src/Template/Layout/ajax/ajax.ctp');
    }

    /**
     * Test for `view()` method
     */
    public function testView()
    {
        list($photos) = ($this->InstagramComponent->recent());
        $id = $photos[0]->id;

        $this->get(['_name' => 'instagramPhoto', $id]);
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $this->assertTemplate(ROOT . 'src/Template/Instagram/view.ctp');

        $photoFromView = $this->viewVariable('photo');
        $this->assertInstanceof('stdClass', $photoFromView);

        $photoFromCache = Cache::read(sprintf('media_%s', md5($id)), 'instagram');
        $this->assertEquals($photoFromView, $photoFromCache);
    }

    /**
     * Test for `view()` method, with invalid ID
     */
    public function testViewInvalidId()
    {
        $this->get(['_name' => 'instagramPhoto', '1_1']);
        $this->assertRedirect(['_name' => 'instagramPhotos']);
    }
}
