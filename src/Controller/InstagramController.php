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
use Cake\Network\Exception\NotFoundException;
use MeCms\Controller\AppController;

/**
 * Instagram controller
 */
class InstagramController extends AppController
{
    /**
     * Called after the controller action is run, but before the view is
     * rendered.
     * You can use this method to perform logic or set view variables that
     * are required on every request.
     * @param \Cake\Event\Event $event An Event instance
     * @return void
     * @see http://api.cakephp.org/3.4/class-Cake.Controller.Controller.html#_beforeRender
     * @uses MeCms\Controller\AppController::beforeRender()
     * @uses MeCmsInstagram\Utility\Instagram::user()
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        //Tries to get data from the cache
        $user = Cache::read($cache = 'user_profile', 'instagram');

        //If the data are not available from the cache
        if (empty($user)) {
            $user = $this->Instagram->user();

            Cache::write($cache, $user, 'instagram');
        }

        $this->set(compact('user'));
    }

    /**
     * Initialization hook method
     * @return void
     * @uses MeCms\Controller\AppController::initialize()
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent(ME_CMS_INSTAGRAM . '.Instagram');
    }

    /**
     * Lists photos from Istangram
     * @param string $id Request ID ("Next ID" for Istangram)
     * @return void
     * @uses MeCmsInstagram\Utility\Instagram::recent()
     */
    public function index($id = null)
    {
        //Sets initial cache name
        $cache = sprintf('index_limit_%s', getConfigOrFail('default.photos'));

        //Adds the request ID ("Next ID" for Istangram) to the cache name
        if (!empty($id)) {
            $cache = sprintf('%s_id_%s', $cache, $id);
        }

        //Tries to get data from the cache
        list($photos, $nextId) = Cache::read($cache, 'instagram');

        //If the data are not available from the cache
        if (empty($photos)) {
            list($photos, $nextId) = $this->Instagram->recent($id, getConfigOrFail('default.photos'));

            Cache::write($cache, [$photos, $nextId], 'instagram');
        }

        $this->set(compact('photos', 'nextId'));
    }

    /**
     * Views a photo
     * @param string $id Media ID
     * @return \Cake\Network\Response|null|void
     * @uses MeCmsInstagram\Utility\Instagram::media()
     */
    public function view($id)
    {
        //Tries to get data from the cache
        $photo = Cache::read($cache = sprintf('media_%s', md5($id)), 'instagram');

        //If the data are not available from the cache
        if (empty($photo)) {
            //It tries to get the media (photo). If an exception is thrown, redirects to index
            try {
                $photo = $this->Instagram->media($id);
            } catch (NotFoundException $e) {
                return $this->redirect(['_name' => 'instagramPhotos'], 301);
            }

            Cache::write($cache, $photo, 'instagram');
        }

        $this->set(compact('photo'));
    }
}
