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

use Cake\Cache\Cache;
use Cake\Core\Configure;

/**
 * MeInstagram configuration
 */
//Loads the configuration from the plugin
Configure::load('MeInstagram.me_instagram');

$config = Configure::read('MeInstagram');

//Loads the configuration from the application, if exists
if(is_readable(CONFIG.'me_instagram.php')) {
	Configure::load('me_instagram', 'default', FALSE);
	
	$config = \Cake\Utility\Hash::mergeDiff(Configure::consume('MeInstagram'), $config);
}

Configure::write('MeCms', \Cake\Utility\Hash::mergeDiff(Configure::read('MeCms'), $config));

/**
 * Instagram keys 
 */
//Loads the Instagram keys
Configure::load('instagram_keys');

/**
 * Loads the cache configuration
 */
Configure::load('MeInstagram.cache');

//Merges with the configuration from application, if exists
if(is_readable(CONFIG.'cache.php'))
	Configure::load('cache');
    
//Adds all cache configurations
foreach(Configure::consume('Cache') as $key => $config) {
	//Drops cache configurations that already exist
	if(Cache::config($key))
		Cache::drop($key);
	
	Cache::config($key, $config);
}