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
 * @since       1.8.0
 */
namespace App;

use Assets\Plugin as Assets;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\RoutingMiddleware;
use MeCmsInstagram\Plugin as MeCmsInstagram;
use RecaptchaMailhide\Plugin as RecaptchaMailhide;
use Thumber\Plugin as Thumber;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * Load all the application configuration and bootstrap logic
     */
    public function bootstrap()
    {
        $this->addPlugin(Assets::class, ['routes' => false]);
        $this->addPlugin(RecaptchaMailhide::class, ['routes' => false]);
        $this->addPlugin(Thumber::class, ['routes' => false]);
        $this->addPlugin(MeCmsInstagram::class, ['bootstrap' => false, 'routes' => false]);
    }

    /**
     * Define the HTTP middleware layers for an application
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to set in your App Class
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue->add(new RoutingMiddleware($this));

        return $middlewareQueue;
    }
}
