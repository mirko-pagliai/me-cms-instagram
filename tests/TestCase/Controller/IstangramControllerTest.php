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
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\IntegrationTestCase;
use MeCmsInstagram\Controller\InstagramController;
use MeCmsInstagram\Utility\Instagram;

/**
 * IstangramControllerTest class
 */
class IstangramControllerTest extends IntegrationTestCase
{
    /**
     * @var \MeCmsInstagram\Controller\InstagramController
     */
    protected $InstagramController;

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

        $this->InstagramController = $this->getMockBuilder(InstagramController::class)
            ->setConstructorArgs([new Request(), new Response()])
            ->setMethods(['_getInstagramInstance'])
            ->getMock();

        $this->InstagramController->method('_getInstagramInstance')
            ->will($this->returnCallback(function () {
                $instagram = $this->getMockBuilder(Instagram::class)
                    ->setMethods(['_getMediaResponse', '_getRecentResponse', '_getUserResponse'])
                    ->getMock();

                $instagram->expects($this->any())
                    ->method('_getMediaResponse')
                    ->will($this->returnCallback(function () {
                        return file_get_contents(TEST_APP . 'examples' . DS . 'media.json');
                    }));

                $instagram->expects($this->any())
                    ->method('_getRecentResponse')
                    ->will($this->returnCallback(function () {
                        return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
                    }));

                $instagram->expects($this->any())
                    ->method('_getUserResponse')
                    ->will($this->returnCallback(function () {
                        return file_get_contents(TEST_APP . 'examples' . DS . 'user.json');
                    }));

                return $instagram;
            }));

        $this->InstagramController->viewBuilder()->setPlugin(ME_CMS_INSTAGRAM);
        $this->InstagramController->viewBuilder()->setTemplatePath('Instagram');
        $this->InstagramController->viewBuilder()->setLayout(false);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->InstagramController);
    }

    /**
     * Test for `index()` method
     */
    public function testIndex()
    {
        $this->InstagramController->index(1);
        $response = $this->InstagramController->render('index');

        $this->assertNotEmpty(trim($response->getBody()));
    }

    /**
     * Test for `view()` method
     */
    public function testView()
    {
        $this->InstagramController->view(1);
        $response = $this->InstagramController->render('view');

        $this->assertNotEmpty(trim($response->getBody()));
    }

    /**
     * Test for `view()` method, with invalid media Id
     */
    public function testViewInvalidMediaId()
    {
        $this->InstagramController = $this->getMockBuilder(InstagramController::class)
            ->setConstructorArgs([new Request(), new Response()])
            ->setMethods(['redirect'])
            ->getMock();

        $this->InstagramController->method('redirect')
             ->willReturn('called redirect');

        $this->assertEquals('called redirect', $this->InstagramController->view(1));
    }

    /**
     * Test withoud mocking the `Instagram` object
     * @expectedException Cake\Network\Exception\NotFoundException
     * @expectedExceptionMessage Record not found
     * @test
     */
    public function testWithoutMockingInstagram()
    {
        (new InstagramController)->index();
    }
}
