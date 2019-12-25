<?php
declare(strict_types=1);

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

namespace MeCmsInstagram\Controller;

use Cake\Cache\Cache;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use MeCms\Controller\AppController;

/**
 * Instagram controller
 */
class InstagramController extends AppController
{
    /**
     * Called after the controller action is run, but before the view is rendered
     * You can use this method to perform logic or set view variables that
     * are required on every request.
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return void
     * @uses \MeCmsInstagram\Utility\Instagram::user()
     */
    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        $this->set('user', Cache::remember('user_profile', [$this->Instagram, 'user'], 'instagram'));
    }

    /**
     * Initialization hook method
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('MeCmsInstagram.Instagram');
    }

    /**
     * Lists photos from Istangram
     * @param string|null $id Request ID ("Next ID" for Istangram)
     * @return void
     * @uses \MeCmsInstagram\Utility\Instagram::recent()
     */
    public function index(?string $id = null): void
    {
        //Sets initial cache name
        $cache = sprintf('index_limit_%s', getConfigOrFail('default.photos'));

        //Adds the request ID ("Next ID" for Istangram) to the cache name
        if ($id) {
            $cache = sprintf('%s_id_%s', $cache, $id);
        }

        [$photos, $nextId] = Cache::remember($cache, function () use ($id) {
            return $this->Instagram->recent($id, getConfigOrFail('default.photos'));
        }, 'instagram');

        $this->set(compact('photos', 'nextId'));
    }

    /**
     * Views a photo
     * @param string $id Media ID
     * @return \Cake\Http\Response|null|void
     * @uses \MeCmsInstagram\Utility\Instagram::media()
     */
    public function view(string $id)
    {
        //It tries to get the media (photo). If an exception is thrown, redirects to index
        try {
            $photo = Cache::remember(sprintf('media_%s', md5($id)), function () use ($id) {
                return $this->Instagram->media($id);
            }, 'instagram');
        } catch (NotFoundException $e) {
            return $this->redirect(['_name' => 'instagramPhotos'], 301);
        }

        $this->set(compact('photo'));
    }
}
