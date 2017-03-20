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

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Hash;

//Sets the default me-cms-instagram name
if (!defined('ME_CMS_INSTAGRAM')) {
    define('ME_CMS_INSTAGRAM', 'MeCmsInstagram');
}

/**
 * Loads the me-cms-instagram configuration
 */
Configure::load(sprintf('%s.me_cms_instagram', ME_CMS_INSTAGRAM));

//Merges with the configuration from application, if exists
if (is_readable(CONFIG . 'me_cms_instagram.php')) {
    Configure::load('me_cms_instagram');
}

//Merges with the MeCms configuration
Configure::write(ME_CMS, Hash::merge(config(ME_CMS), Configure::consume(ME_CMS_INSTAGRAM)));

if (!config('Instagram.key') || config('Instagram.key') === 'your-key-here') {
    throw new InternalErrorException('Instagram API access token is missing');
}

/**
 * Loads the cache configuration
 */
Configure::load(sprintf('%s.cache', ME_CMS_INSTAGRAM));

//Merges with the configuration from application, if exists
if (is_readable(CONFIG . 'cache.php')) {
    Configure::load('cache');
}

//Adds all cache configurations
foreach (Configure::consume('Cache') as $key => $config) {
    //Drops cache configurations that already exist
    if (Cache::getConfig($key)) {
        Cache::drop($key);
    }

    Cache::setConfig($key, $config);
}
