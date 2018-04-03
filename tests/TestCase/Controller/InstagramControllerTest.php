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
namespace MeCmsInstagram\Test\TestCase\Controller;

use Cake\Cache\Cache;
use Cake\Controller\ComponentRegistry;
use MeCmsInstagram\Controller\Component\InstagramComponent;
use MeTools\TestSuite\IntegrationTestCase;

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
        $methods = [
            'getMediaResponse' => 'media.json',
            'getRecentResponse' => 'recent.json',
            'getUserResponse' => 'user.json',
        ];

        $instance = $this->getMockBuilder(InstagramComponent::class)
            ->setConstructorArgs([new ComponentRegistry])
            ->setMethods(array_keys($methods))
            ->getMock();

        foreach ($methods as $method => $filename) {
            $instance->method($method)
                ->will($this->returnValue(file_get_contents(TEST_APP . 'examples' . DS . $filename)));
        }

        return $instance;
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
        $this->assertInstanceof('Cake\ORM\Entity', $userFromView);
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
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src/Template/Instagram/index.ctp');

        $photosFromView = $this->viewVariable('photos');
        $this->assertNotEmpty($photosFromView);
        $this->assertInstanceof('Cake\ORM\Entity', $photosFromView);

        $nextIdFromView = $this->viewVariable('nextId');
        $this->assertEquals('111_222', $nextIdFromView);

        //Sets the cache name
        $cache = sprintf('index_limit_%s', getConfigOrFail('default.photos'));
        list($photosFromCache, $nextIdFromCache) = array_values(Cache::read($cache, 'instagram'));

        $this->assertEquals($photosFromView, $photosFromCache);
        $this->assertEquals($nextIdFromView, $nextIdFromCache);

        //GET request. Now with the `nextId`
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src/Template/Instagram/index.ctp');

        //GET request. Now it's an ajax request, with the `nextId`
        $this->configRequest(['headers' => ['X-Requested-With' => 'XMLHttpRequest']]);
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src/Template/Instagram/index.ctp');
        $this->assertLayout('src/Template/Layout/ajax/ajax.ctp');
    }

    /**
     * Test for `view()` method
     */
    public function testView()
    {
        $id = $this->InstagramComponent->recent()[0][0]->id;

        $this->get(['_name' => 'instagramPhoto', $id]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src/Template/Instagram/view.ctp');

        $photoFromView = $this->viewVariable('photo');
        $this->assertInstanceof('Cake\ORM\Entity', $photoFromView);

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
