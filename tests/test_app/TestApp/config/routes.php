<?php
use Cake\Routing\Router;

Router::scope('/', function ($routes) {
    $routes->loadPlugin(THUMBER);
    $routes->loadPlugin('MeCmsInstagram');
});
