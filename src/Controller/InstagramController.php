<?php
/**
 * This file is part of MeInstagram.
 *
 * MeInstagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeInstagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeInstagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeInstagram\Controller;

use Cake\Network\Exception\NotFoundException;
use MeCms\Controller\AppController;
use MeInstagram\Utility\Instagram;
use Cake\Cache\Cache;

/**
 * Instagram controller
 */
class InstagramController extends AppController {
    /**
	 * Called after the controller action is run, but before the view is rendered.
	 * You can use this method to perform logic or set view variables that are required on every request.
	 * @param \Cake\Event\Event $event An Event instance
	 * @see http://api.cakephp.org/3.2/class-Cake.Controller.Controller.html#_beforeRender
	 * @uses MeCms\Controller\AppController::beforeRender()
	 * @uses MeInstagram\Utility\Instagram::user()
	 */
	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
		
		//Tries to get data from the cache
		$user = Cache::read($cache = 'user_profile', 'instagram');
        
        //If the data are not available from the cache
		if(empty($user)) {
			$user = Instagram::user();
			
			Cache::write($cache, $user, 'instagram');
		}
        
		$this->set(compact('user'));
	}
	
	/**
	 * Lists photos from Istangram
	 * @param string $id Request ID ("Next ID" for Istangram)
	 * @uses MeInstagram\Utility\Instagram::recent()
	 */
	public function index($id = NULL) {
        //Sets initial cache name
		$cache = sprintf('index_limit_%s', config('frontend.photos'));
		
		//Adds the request ID ("Next ID" for Istangram) to the cache name
		if(!empty($id)) {
			$cache = sprintf('%s_id_%s', $cache, $id);
        }
        
		//Tries to get data from the cache
        list($photos, $next_id) = Cache::read($cache, 'instagram');
        
		//If the data are not available from the cache
		if(empty($photos)) {
            list($photos, $next_id) = Instagram::recent($id, config('frontend.photos'));
            
			Cache::write($cache, [$photos, $next_id], 'instagram');
        }

        $this->set(compact('photos', 'next_id'));
	}
	
	/**
	 * Views a photo
	 * @param string $id Media ID
	 * @uses MeInstagram\Utility\Instagram::media()
	 */
	public function view($id) {
		//Tries to get data from the cache
		$photo = Cache::read($cache = sprintf('media_%s', md5($id)), 'instagram');
        
		//If the data are not available from the cache
		if(empty($photo)) {
            //It tries to get the media (photo). If an exception is thrown, redirects to index
            try {
                $photo = Instagram::media($id);
            }
            catch(NotFoundException $e) {
                return $this->redirect(['action' => 'index'], 301);
            }
            
			Cache::write($cache, $photo, 'instagram');
        }
        
		$this->set(compact('photo'));
	}
}