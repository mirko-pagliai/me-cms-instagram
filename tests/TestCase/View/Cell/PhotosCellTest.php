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

use Cake\TestSuite\TestCase;
use MeCmsInstagram\Utility\Instagram;
use MeCmsInstagram\View\Cell\PhotosCell;
use MeCms\View\View\AppView;

/**
 * PhotosCellTest class
 */
class PhotosCellTest extends TestCase
{
    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->PhotosCell = $this->getMockBuilder(PhotosCell::class)
            ->setMethods(['_getInstagramInstance'])
            ->setConstructorArgs([$this->getMockBuilder('Cake\Network\Request')->getMock()])
            ->getMock();

        $this->PhotosCell->expects($this->any())
            ->method('_getInstagramInstance')
            ->will($this->returnCallback(function () {
                $instagram = $this->getMockBuilder(Instagram::class)
                    ->setMethods(['_getRecentResponse'])
                    ->getMock();

                $instagram->expects($this->any())
                    ->method('_getRecentResponse')
                    ->will($this->returnCallback(function () {
                        return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
                    }));

                return $instagram;
            }));

        $this->PhotosCell->viewBuilder()->plugin(ME_CMS_INSTAGRAM);
        $this->PhotosCell->viewBuilder()->templatePath('Cell/Photos');
        $this->PhotosCell->viewClass = get_class(new AppView);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->PhotosCell);
    }

    /**
     * Test for `latest()` method
     * @test
     */
    public function testLatest()
    {
        $this->PhotosCell->action = $this->PhotosCell->template = 'latest';

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
        $result = preg_replace('/(\n|\s{2})/', '', $this->PhotosCell->render());
        $this->assertHtml($expected, $result);
        $this->assertEquals('latest', $this->PhotosCell->template);

        $this->PhotosCell->args = ['limit' => 2];

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
        $result = preg_replace('/(\n|\s{2})/', '', $this->PhotosCell->render());
        $this->assertHtml($expected, $result);
    }

    /**
     * Test for `random()` method
     * @test
     */
    public function testRandom()
    {
        $this->PhotosCell->action = $this->PhotosCell->template = 'random';

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
        $result = preg_replace('/(\n|\s{2})/', '', $this->PhotosCell->render());
        $this->assertHtml($expected, $result);
        $this->assertEquals('random', $this->PhotosCell->template);

        $this->PhotosCell->args = ['limit' => 2];

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
        $result = preg_replace('/(\n|\s{2})/', '', $this->PhotosCell->render());
        $this->assertHtml($expected, $result);
    }
}
