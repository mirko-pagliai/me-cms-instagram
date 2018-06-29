<?php
use Cake\Routing\Router;

Router::scope('/', function ($routes) {
    $routes->loadPlugin(THUMBER);
    $routes->loadPlugin(ME_CMS_INSTAGRAM);
});
