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
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use RuntimeException;

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
Configure::write(ME_CMS, Hash::merge(getConfig(ME_CMS), Configure::consume(ME_CMS_INSTAGRAM)));

if (getConfigOrFail('Instagram.key') === 'your-key-here') {
    throw new RuntimeException('Instagram API access token is missing');
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
