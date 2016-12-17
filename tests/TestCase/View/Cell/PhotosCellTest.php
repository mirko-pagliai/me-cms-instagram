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
namespace MeCmsInstagram\Test\TestCase\View\Cell;

use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\TestCase;
use MeCmsInstagram\Utility\Instagram;
use TestApp\View\AppView as View;

/**
 * PhotosCellTest class
 */
class PhotosCellTest extends TestCase
{
    /**
     * @var \TestApp\View\AppView
     */
    protected $View;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Configure::write('App.namespace', 'TestApp');

        $this->View = new View(
            $this->getMockBuilder(Request::class)->getMock(),
            $this->getMockBuilder(Response::class)->getMock(),
            new EventManager
        );
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->View);
    }

    /**
     * Test for `latest()` method
     * @test
     */
    public function testLatest()
    {
        $mock = $this->getMockBuilder(Instagram::class)
            ->setMethods(['_getRecentResponse'])
            ->getMock();

        $mock->expects($this->any())
            ->method('_getRecentResponse')
            ->will($this->returnCallback(function () {
                return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
            }));

        $cell = $this->View->cell('MeCmsInstagram.Photos::latest', ['limit' => 1], ['instagramInstance' => $mock]);

        $expected = [
            ['div' => ['class' => 'widget']],
            'h4' => ['class' => 'widget-title'],
            'Latest photo',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            'a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 1'],
            'img' => ['src', 'alt', 'class' => 'img-responsive'],
            '/a',
            '/div',
            '/div',
        ];

        //Removes new lines and spaces from render
        $result = preg_replace('/(\n|\s{2})/', '', $cell->render());
        $this->assertHtml($expected, $result);
        $this->assertEquals('latest', $cell->template);

        $cell = $this->View->cell('MeCmsInstagram.Photos::latest', ['limit' => 2], ['instagramInstance' => $mock]);

        $expected = [
            ['div' => ['class' => 'widget']],
            'h4' => ['class' => 'widget-title'],
            'Latest 2 photos',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 1']],
            ['img' => ['src', 'alt', 'class' => 'img-responsive']],
            '/a',
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 2']],
            ['img' => ['src', 'alt', 'class' => 'img-responsive']],
            '/a',
            '/div',
            '/div',
        ];

        //Removes new lines and spaces from render
        $result = preg_replace('/(\n|\s{2})/', '', $cell->render());
        $this->assertHtml($expected, $result);
    }

    /**
     * Test for `random()` method
     * @test
     */
    public function testRandom()
    {
        $mock = $this->getMockBuilder(Instagram::class)
            ->setMethods(['_getRecentResponse'])
            ->getMock();

        $mock->expects($this->any())
            ->method('_getRecentResponse')
            ->will($this->returnCallback(function () {
                return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
            }));

        $cell = $this->View->cell('MeCmsInstagram.Photos::random', ['limit' => 1], ['instagramInstance' => $mock]);

        $expected = [
            ['div' => ['class' => 'widget']],
            'h4' => ['class' => 'widget-title'],
            'Random photo',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            'a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/'],
            'img' => ['src', 'alt', 'class' => 'img-responsive'],
            '/a',
            '/div',
            '/div',
        ];

        //Removes new lines and spaces from render
        $result = preg_replace('/(\n|\s{2})/', '', $cell->render());
        $this->assertHtml($expected, $result);
        $this->assertEquals('random', $cell->template);

        $cell = $this->View->cell('MeCmsInstagram.Photos::random', ['limit' => 2], ['instagramInstance' => $mock]);

        $expected = [
            ['div' => ['class' => 'widget']],
            'h4' => ['class' => 'widget-title'],
            'Random 2 photos',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/']],
            ['img' => ['src', 'alt', 'class' => 'img-responsive']],
            '/a',
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/']],
            ['img' => ['src', 'alt', 'class' => 'img-responsive']],
            '/a',
            '/div',
            '/div',
        ];

        //Removes new lines and spaces from render
        $result = preg_replace('/(\n|\s{2})/', '', $cell->render());
        $this->assertHtml($expected, $result);
    }
}
