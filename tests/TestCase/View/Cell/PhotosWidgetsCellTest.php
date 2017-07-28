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
use MeCms\View\Helper\WidgetHelper;
use MeCms\View\View\AppView as View;
use MeTools\TestSuite\TestCase;

/**
 * PhotosWidgetsCellTest class
 */
class PhotosWidgetsCellTest extends TestCase
{
    /**
     * @var \MeCms\View\Helper\WidgetHelper
     */
    protected $Widget;

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

        $this->Widget = $this->getMockBuilder(WidgetHelper::class)
            ->setConstructorArgs([new View])
            ->setMethods(['widget'])
            ->getMock();

        $this->Widget->method('widget')->will($this->returnCallback(function () {
            $widgetClass = call_user_func_array([new WidgetHelper(new View), 'widget'], func_get_args());

            $widgetClass->Instagram = $this->getMockBuilder(get_class($widgetClass->Instagram))
                ->setMethods(['_getRecentResponse'])
                ->getMock();

            if (in_array($this->getName(), ['testLatestNoPhotos', 'testRandomNoPhotos'])) {
                $widgetClass->Instagram
                    ->method('_getRecentResponse')
                    ->will($this->returnValue(json_encode(['data' => []])));
            } else {
                $widgetClass->Instagram
                    ->method('_getRecentResponse')
                    ->will($this->returnValue(file_get_contents(TEST_APP . 'examples' . DS . 'recent.json')));
            }

            return $widgetClass;
        }));
    }

    /**
     * Test for `latest()` method
     * @test
     */
    public function testLatest()
    {
        $widget = ME_CMS_INSTAGRAM . '.Photos::latest';

        $result = $this->Widget->widget($widget)->render();
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
        $this->assertHtml($expected, $result);

        $result = $this->Widget->widget($widget, ['limit' => 2])->render();
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
        $this->assertHtml($expected, $result);

        //Sets `Instagram` controller
        $widget = $this->Widget->widget(ME_CMS_INSTAGRAM . '.Photos::latest');
        $widget->request = $widget->request->withParam('controller', 'Instagram');
        $this->assertEmpty($widget->render());

        //Tests cache
        $fromCache = Cache::read('widget_latest_1', 'instagram');
        $this->assertEquals(1, count($fromCache));

        $fromCache = Cache::read('widget_latest_2', 'instagram');
        $this->assertEquals(2, count($fromCache));
    }

    /**
     * Test for `latest()` method, with no photos
     * @test
     */
    public function testLatestNoPhotos()
    {
        $this->assertEmpty($this->Widget->widget(ME_CMS_INSTAGRAM . '.Photos::latest')->render());
    }

    /**
     * Test for `random()` method
     * @test
     */
    public function testRandom()
    {
        $widget = ME_CMS_INSTAGRAM . '.Photos::random';

        $result = $this->Widget->widget($widget)->render();
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
        $this->assertHtml($expected, $result);

        $result = $this->Widget->widget($widget, ['limit' => 2])->render();
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
        $this->assertHtml($expected, $result);

        //Sets `Instagram` controller
        $widget = $this->Widget->widget(ME_CMS_INSTAGRAM . '.Photos::random');
        $widget->request = $widget->request->withParam('controller', 'Instagram');
        $this->assertEmpty($widget->render());

        //Tests cache
        $fromCache = Cache::read('widget_latest_12', 'instagram');
        $this->assertEquals(12, count($fromCache));
    }

    /**
     * Test for `random()` method, with no photos
     * @test
     */
    public function testRandomNoPhotos()
    {
        $this->assertEmpty($this->Widget->widget(ME_CMS_INSTAGRAM . '.Photos::random')->render());
    }
}
