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

/**
 * Plugin routes
 */
Router::scope('/', ['plugin' => 'MeCmsInstagram'], function (RouteBuilder $routes) {
    $routes->setExtensions(['rss']);

    //Instagram
    if (!$routes->nameExists('instagramPhotos')) {
        $routes->get('/instagram', ['controller' => 'Instagram', 'action' => 'index'], 'instagramPhotos');
    }

    //Instagram (with ID)
    if (!$routes->nameExists('instagramPhotosId')) {
        $routes->get('/instagram/:id', ['controller' => 'Instagram', 'action' => 'index'], 'instagramPhotosId')
            ->setPatterns(['id' => '\d+_\d+'])
            ->setPass(['id']);
    }

    //Instagram photo
    if (!$routes->nameExists('instagramPhoto')) {
        $routes->get('/instagram/view/:id', ['controller' => 'Instagram', 'action' => 'view'], 'instagramPhoto')
            ->setPatterns(['id' => '\d+_\d+'])
            ->setPass(['id']);
    }
});
