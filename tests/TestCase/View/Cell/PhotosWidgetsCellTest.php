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
namespace MeCmsInstagram\Test\TestCase\View\Cell;

use Cake\Cache\Cache;
use MeCmsInstagram\Utility\Instagram;
use MeCms\TestSuite\CellTestCase;
use MeCms\View\Helper\WidgetHelper;
use MeCms\View\View\AppView as View;

/**
 * PhotosWidgetsCellTest class
 */
class PhotosWidgetsCellTest extends CellTestCase
{
    /**
     * @var bool
     */
    protected $autoInitializeClass = false;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        $view = new View();
        $this->Widget = $this->getMockBuilder(WidgetHelper::class)
            ->setConstructorArgs([$view])
            ->setMethods(['widget'])
            ->getMock();

        $this->Widget->method('widget')->will($this->returnCallback(function () use ($view) {
            $widgetClass = call_user_func_array([new WidgetHelper($view), 'widget'], func_get_args());

            $widgetClass->Instagram = $this->getMockBuilder(Instagram::class)
                ->setMethods(['getRecentResponse'])
                ->getMock();

            $widgetClass->Instagram->method('getRecentResponse')
                ->will($this->returnCallback(function () {
                    if (in_array($this->getName(), ['testLatestNoPhotos', 'testRandomNoPhotos'])) {
                        return json_encode(['data' => []]);
                    }

                    return file_get_contents(TEST_APP . 'examples' . DS . 'recent.json');
                }));

            return $widgetClass;
        }));

        parent::setUp();
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Cache::clear(false, 'instagram');
    }

    /**
     * Test for `latest()` method
     * @test
     */
    public function testLatest()
    {
        $widget = 'MeCmsInstagram.Photos::latest';

        $expected = [
            ['div' => ['class' => 'widget mb-4']],
            'h4' => ['class' => 'widget-title'],
            'Latest photo',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            'a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 1'],
            'img' => ['src', 'alt', 'class' => 'img-fluid'],
            '/a',
            '/div',
            '/div',
        ];
        $this->assertHtml($expected, $this->Widget->widget($widget)->render());

        $expected = [
            ['div' => ['class' => 'widget mb-4']],
            'h4' => ['class' => 'widget-title'],
            'Latest 2 photos',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 1']],
            ['img' => ['src', 'alt', 'class' => 'img-fluid']],
            '/a',
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'Example text 2']],
            ['img' => ['src', 'alt', 'class' => 'img-fluid']],
            '/a',
            '/div',
            '/div',
        ];
        $this->assertHtml($expected, $this->Widget->widget($widget, ['limit' => 2])->render());

        //Tests cache
        $this->assertEquals(1, count(Cache::read('widget_latest_1', 'instagram')));
        $this->assertEquals(2, count(Cache::read('widget_latest_2', 'instagram')));

        //Sets `Instagram` controller
        $request = $this->Widget->getView()->getRequest()->withParam('controller', 'Instagram');
        $this->Widget->getView()->setRequest($request);
        $this->assertEmpty($this->Widget->widget($widget)->render());
    }

    /**
     * Test for `latest()` method, with no photos
     * @test
     */
    public function testLatestNoPhotos()
    {
        $this->assertEmpty($this->Widget->widget('MeCmsInstagram.Photos::latest')->render());
    }

    /**
     * Test for `random()` method
     * @test
     */
    public function testRandom()
    {
        $widget = 'MeCmsInstagram.Photos::random';

        $result = $this->Widget->widget($widget)->render();
        $expected = [
            ['div' => ['class' => 'widget mb-4']],
            'h4' => ['class' => 'widget-title'],
            'Random photo',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            'a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/'],
            'img' => ['src', 'alt', 'class' => 'img-fluid'],
            '/a',
            '/div',
            '/div',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Widget->widget($widget, ['limit' => 2])->render();
        $expected = [
            ['div' => ['class' => 'widget mb-4']],
            'h4' => ['class' => 'widget-title'],
            'Random 2 photos',
            '/h4',
            ['div' => ['class' => 'widget-content']],
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/']],
            ['img' => ['src', 'alt', 'class' => 'img-fluid']],
            '/a',
            ['a' => ['href', 'class' => 'thumbnail', 'title' => 'preg:/Example text \d+/']],
            ['img' => ['src', 'alt', 'class' => 'img-fluid']],
            '/a',
            '/div',
            '/div',
        ];
        $this->assertHtml($expected, $result);

        //Tests cache
        $this->assertEquals(12, count(Cache::read('widget_latest_12', 'instagram')));

        //Sets `Instagram` controller
        $request = $this->Widget->getView()->getRequest()->withParam('controller', 'Instagram');
        $this->Widget->getView()->setRequest($request);
        $this->assertEmpty($this->Widget->widget($widget)->render());
    }

    /**
     * Test for `random()` method, with no photos
     * @test
     */
    public function testRandomNoPhotos()
    {
        $this->assertEmpty($this->Widget->widget('MeCmsInstagram.Photos::random')->render());
    }
}
