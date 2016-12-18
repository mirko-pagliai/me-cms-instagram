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
namespace MeCmsInstagram\Test\TestCase\Utility;

use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use MeCmsInstagram\Utility\Sitemap;

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
