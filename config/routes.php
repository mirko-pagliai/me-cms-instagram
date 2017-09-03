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
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

/**
 * Plugin routes
 */
Router::scope('/', ['plugin' => ME_CMS_INSTAGRAM], function (RouteBuilder $routes) {
    $routes->setExtensions(['rss']);

    //Instagram
    if (!$routes->nameExists('instagramPhotos')) {
        $routes->connect(
            '/instagram',
            ['controller' => 'Instagram', 'action' => 'index'],
            ['_name' => 'instagramPhotos']
        );
    }

    //Instagram (with ID)
    if (!$routes->nameExists('instagramPhotosId')) {
        $routes->connect(
            '/instagram/:id',
            ['controller' => 'Instagram', 'action' => 'index'],
            ['_name' => 'instagramPhotosId', 'id' => '\d+_\d+', 'pass' => ['id']]
        );
    }

    //Instagram photo
    if (!$routes->nameExists('instagramPhoto')) {
        $routes->connect(
            '/instagram/view/:id',
            ['controller' => 'Instagram', 'action' => 'view'],
            ['_name' => 'instagramPhoto', 'id' => '\d+_\d+', 'pass' => ['id']]
        );
    }
});
