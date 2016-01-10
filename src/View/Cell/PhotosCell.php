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
namespace MeInstagram\View\Cell;

use Cake\Cache\Cache;
use Cake\View\Cell;
use MeInstagram\Utility\Instagram;

/**
 * Photos cell
 */
class PhotosCell extends Cell {	
	/**
	 * Random widget
	 * @param int $limit Limit
	 * @uses MeInstagram\Utility\Instagram::getRandom()
	 * @uses MeTools\Network\Request::isController()
	 */
	public function random($limit = 1) {
		//Returns on the same controller
		if($this->request->isController('Instagram'))
			return;
		
		//Returns, if there are no photos available
		if(Cache::read($cache = 'no_photos', 'instagram'))
			return;
		
		//Gets photos
		$photos = Instagram::getRandom($limit);
				
		//Writes on cache, if there are no photos available
		if(empty($photos))
			Cache::write($cache, TRUE, 'instagram');
		
		$this->set(compact('photos'));
	}
}