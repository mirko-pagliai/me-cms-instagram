<?php

use Cake\Routing\Router;

Router::scope('/', function ($routes) {
    $routes->loadPlugin('Thumber');
    $routes->loadPlugin('MeCmsInstagram');
});
