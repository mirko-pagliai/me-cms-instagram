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

use Cake\Routing\Router;
use MeCmsInstagram\Utility\Sitemap;
use MeTools\TestSuite\TestCase;

/**
 * SitemapTest class
 */
class SitemapTest extends TestCase
{
    /**
     * Test for `instagram()` method
     * @test
     */
    public function testInstagram()
    {
        $this->assertEquals([
            [
                'loc' => Router::url(['_name' => 'instagramPhotos'], true),
                'priority' => '0.5',
            ],
        ], Sitemap::instagram());
    }
}
