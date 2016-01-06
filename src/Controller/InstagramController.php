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

use Cake\Cache\Cache;
use MeCms\Controller\AppController;
use MeInstagram\Utility\Instagram;

/**
 * Instagram controller
 */
class InstagramController extends AppController {
	/**
	 * Gets the user's profile
	 * @return object
	 * @uses MeInstagram\Utility\Instagram::getUser()
	 */
	protected function __getUser() {
		//Tries to get data from the cache
		$user = Cache::read($cache = 'user', 'instagram');
		
		//If the data are not available from the cache
		if(empty($user)) {
			//Gets the user
			$user = Instagram::getUser();
			
			Cache::write($cache, $user, 'instagram');
		}
		
		return $user;
	}
	
	/**
	 * Called after the controller action is run, but before the view is rendered.
	 * You can use this method to perform logic or set view variables that are required on every request.
	 * @param \Cake\Event\Event $event An Event instance
	 * @see http://api.cakephp.org/3.1/class-Cake.Controller.Controller.html#_beforeRender
	 * @uses MeCms\Controller\AppController::beforeRender()
	 * @uses __getUser()
	 */
	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
			
		//Gets and sets the user's profile
		$this->set(['user' => $this->__getUser()]);
	}
	
	/**
	 * Lists photos from Istangram
	 * @uses MeInstagram\Utility\Instagram::getRecentUser()
	 */
	public function index() {		
		//Tries to get data from the cache
		$photos = Cache::read($cache = sprintf('index_limit_%s', config('MeInstagram.photos.photos')), 'instagram');
		
		//If the data are not available from the cache
		if(empty($photos)) {
			//Gets the recent medias for the user
			$photos = Instagram::getRecentUser();
			
			Cache::write($cache, $photos, 'instagram');
		}
		
		$this->set(compact('photos'));
	}
	
	/**
	 * Views a photo
	 * @param string $id Media ID
	 * @uses MeInstagram\Utility\Instagram::getMedia()
	 */
	public function view($id) {
		//Tries to get data from the cache
		$photo = Cache::read($cache = sprintf('view_%s', md5($id)), 'instagram');
		
		//If the data are not available from the cache
		if(empty($photo)) {
			//Gets the media
			$photo = Instagram::getMedia($id);
			
			Cache::write($cache, $photo, 'instagram');
		}
		
		$this->set(compact('photo'));
	}
}