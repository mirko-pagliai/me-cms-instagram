<?php
/**
 * This file is part of me-cms-instagram.
 *
 * me-cms-instagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * me-cms-instagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with me-cms-instagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeCmsInstagram\Controller;

use Cake\Cache\Cache;
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
    public function beforeRender(\Cake\Event\Event $event)
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
