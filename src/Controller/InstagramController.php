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

namespace MeCmsInstagram\Controller;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use MeCms\Controller\AppController;

/**
 * Instagram controller
 */
class InstagramController extends AppController
{
    /**
     * Called after the controller action is run, but before the view is rendered
     * @param \Cake\Event\Event $event An Event instance
     * @return void
     * @uses \MeCmsInstagram\Utility\Instagram::user()
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->set('user', Cache::remember('user_profile', [$this->Instagram, 'user'], 'instagram'));
    }

    /**
     * Initialization hook method
     * @return void
     */
    public function initialize()
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
    public function index($id = null)
    {
        //Sets initial cache name
        $cache = sprintf('index_limit_%s', getConfigOrFail('default.photos'));

        //Adds the request ID ("Next ID" for Istangram) to the cache name
        if ($id) {
            $cache = sprintf('%s_id_%s', $cache, $id);
        }

        list($photos, $nextId) = Cache::remember($cache, function () use ($id) {
            return $this->Instagram->recent($id, getConfigOrFail('default.photos'));
        }, 'instagram');

        $this->set(compact('photos', 'nextId'));
    }

    /**
     * Views a photo
     * @param string $id Media ID
     * @return \Cake\Network\Response|null|void
     * @uses \MeCmsInstagram\Utility\Instagram::media()
     */
    public function view($id)
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
