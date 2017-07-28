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
 * @see         MeCms\Utility\SitemapBuilder
 */
namespace MeCmsInstagram\Utility;

use MeCms\Utility\SitemapBuilder;

/**
 * This class contains methods called by the `SitemapBuilder`.
 * Each method must be return an array or urls to add to the sitemap.
 *
 * This helper contains methods that will be called automatically to generate
 * the menu of the admin layout.
 * You do not need to call these methods manually.
 */
class Sitemap extends SitemapBuilder
{
    /**
     * Method that returns instagram urls
     * @return array
     * @uses MeCms\Utility\SitemapBuilder::parse()
     */
    public static function instagram()
    {
        //Adds Instagram index
        $url = [
            self::parse(['_name' => 'instagramPhotos']),
        ];

        return $url;
    }
}
