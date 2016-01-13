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

use MeCms\Controller\AppController;
use MeInstagram\Utility\Instagram;

/**
 * Instagram controller
 */
class InstagramController extends AppController {
	/**
	 * Called after the controller action is run, but before the view is rendered.
	 * You can use this method to perform logic or set view variables that are required on every request.
	 * @param \Cake\Event\Event $event An Event instance
	 * @see http://api.cakephp.org/3.1/class-Cake.Controller.Controller.html#_beforeRender
	 * @uses MeCms\Controller\AppController::beforeRender()
	 * @uses MeInstagram\Utility\Instagram::getUserProfile()
	 */
	public function beforeRender(\Cake\Event\Event $event) {
		parent::beforeRender($event);
			
		//Gets and sets the user's profile
		$this->set(['user' => Instagram::getUserProfile()]);
	}
	
	/**
	 * Lists photos from Istangram
	 * @param string $id Request ID ("Next ID" for Istangram)
	 * @uses MeInstagram\Utility\Instagram::getRecentUser()
	 */
	public function index($id = NULL) {
		$photos = Instagram::getRecentUser($id, config('MeInstagram.frontend.photos'));
		
		$this->set([
			'next_id'	=> empty($photos['pagination']['next_max_id']) ? NULL : $photos['pagination']['next_max_id'],
			'photos'	=> $photos['data']
		]);
	}
	
	/**
	 * Views a photo
	 * @param string $id Media ID
	 * @uses MeInstagram\Utility\Instagram::getMedia()
	 */
	public function view($id) {
		$this->set(['photo' => Instagram::getMedia($id)]);
	}
}