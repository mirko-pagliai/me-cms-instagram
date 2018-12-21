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
use Cake\ORM\Entity;
use MeCmsInstagram\Controller\Component\InstagramComponent;
use MeCms\TestSuite\ControllerTestCase;

/**
 * InstagramControllerTest class
 */
class InstagramControllerTest extends ControllerTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $Component;

    /**
     * Internal method to get a mock instance of `InstagramComponent`
     */
    protected function getInstagramComponentMock()
    {
        $methodsAndValuesToReturn = [
            'getMediaResponse' => 'media.json',
            'getRecentResponse' => 'recent.json',
            'getUserResponse' => 'user.json',
        ];
        $methods = array_keys($methodsAndValuesToReturn);

        $instance = $this->getMockForComponent(InstagramComponent::class, $methods);

        foreach ($methodsAndValuesToReturn as $method => $filename) {
            $instance->method($method)
                ->will($this->returnValue(file_get_contents(TEST_APP . 'examples' . DS . $filename)));
        }

        return $instance;
    }

    /**
     * Called once before test methods in a case are started
     */
    public static function setupBeforeClass()
    {
        parent::setupBeforeClass();

        Cache::clearAll();
    }

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Component = $this->getInstagramComponentMock();

    }

    /**
     * Adds additional event spies to the controller/view event manager
     * @param \Cake\Event\Event $event A dispatcher event
     * @param \Cake\Controller\Controller|null $controller Controller instance
     * @return void
     */
    public function controllerSpy($event, $controller = null)
    {
        parent::controllerSpy($event, $controller);

        //Mocks the `InstagramComponent`, expect for the testViewInvalidId` method
        if ($this->getName() !== 'testViewInvalidId') {
            $this->_controller->Instagram = $this->getInstagramComponentMock();
        }
    }

    /**
     * Test for `beforeRender()` method
     */
    public function testBeforeRender()
    {
        $this->get(['_name' => 'instagramPhotos']);
        $this->assertInstanceof(Entity::class, $this->viewVariable('user'));

        $userFromCache = Cache::read('user_profile', 'instagram');
        $this->assertEquals($this->viewVariable('user'), $userFromCache);
    }

    /**
     * Test for `index()` method
     */
    public function testIndex()
    {
        $this->get(['_name' => 'instagramPhotos']);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src' . DS . 'Template' . DS . 'Instagram' . DS . 'index.ctp');
        $this->assertContainsInstanceof(Entity::class, $this->viewVariable('photos'));

        $nextIdFromView = $this->viewVariable('nextId');
        $this->assertEquals('111_222', $nextIdFromView);

        //Sets the cache name
        $cache = sprintf('index_limit_%s', getConfigOrFail('default.photos'));
        list($photosFromCache, $nextIdFromCache) = array_values(Cache::read($cache, 'instagram'));

        $this->assertEquals($this->viewVariable('photos'), $photosFromCache);
        $this->assertEquals($nextIdFromView, $nextIdFromCache);

        //GET request. Now with the `nextId`
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src' . DS . 'Template' . DS . 'Instagram' . DS . 'index.ctp');

        //GET request. Now it's an ajax request, with the `nextId`
        $this->configRequest(['headers' => ['X-Requested-With' => 'XMLHttpRequest']]);
        $this->get(['_name' => 'instagramPhotosId', $nextIdFromView]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src' . DS . 'Template' . DS . 'Instagram' . DS . 'index.ctp');
        $this->assertLayout('src' . DS . 'Template' . DS . 'Layout' . DS . 'ajax' . DS . 'ajax.ctp');
    }

    /**
     * Test for `view()` method
     */
    public function testView()
    {
        $id = $this->Component->recent()[0][0]->id;

        $this->get(['_name' => 'instagramPhoto', $id]);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('src' . DS . 'Template' . DS . 'Instagram' . DS . 'view.ctp');
        $this->assertInstanceof(Entity::class, $this->viewVariable('photo'));

        $photoFromCache = Cache::read(sprintf('media_%s', md5($id)), 'instagram');
        $this->assertEquals($this->viewVariable('photo'), $photoFromCache);
    }

    /**
     * Test for `view()` method, with invalid ID
     */
    public function testViewInvalidId()
    {
        $this->get(['_name' => 'instagramPhoto', '1_1']);
        $this->assertRedirect(['_name' => 'instagramPhotos']);
        $this->assertResponseCode(301);
    }
}
