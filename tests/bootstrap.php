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
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;

ini_set('intl.default_locale', 'en_US');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Path constants to a few helpful things.
define('ROOT', dirname(__DIR__) . DS);
define('VENDOR', ROOT . 'vendor' . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . 'tests' . DS);
define('TEST_APP', TESTS . 'test_app' . DS);
define('APP', TEST_APP . 'TestApp' . DS);
define('APP_DIR', 'TestApp');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS . 'me_cms_instagram' . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP);
define('LOGS', TMP);
define('SESSIONS', TMP . 'sessions' . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config' . DS . 'bootstrap.php';

error_reporting(E_ALL & ~E_USER_DEPRECATED);

safe_mkdir(LOGS);
safe_mkdir(SESSIONS);
safe_mkdir(CACHE);
safe_mkdir(CACHE . 'views');
safe_mkdir(CACHE . 'models');

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => [
        'plugins' => [APP . 'Plugin' . DS],
        'templates' => [
            APP . 'Template' . DS,
            ROOT . 'src' . DS . 'Template' . DS,
        ],
    ],
]);

Cache::setConfig([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
    '_cake_model_' => [
        'engine' => 'File',
        'prefix' => 'cake_model_',
        'serialize' => true,
    ],
    'default' => [
        'engine' => 'File',
        'prefix' => 'default_',
        'serialize' => true,
    ],
]);

// Ensure default test connection is defined
ConnectionManager::setConfig('test', [
    'url' => 'sqlite://127.0.0.1/' . TMP . 'debug_kit_test.sqlite',
    'timezone' => 'UTC',
]);

Configure::write('Session', ['defaults' => 'php']);

/**
 * Loads plugins
 */
Configure::write('Assets.target', TMP . 'assets');
Configure::write('DatabaseBackup.connection', 'test');
Configure::write('DatabaseBackup.target', TMP . 'backups');

foreach (['bzip2', 'gzip', 'mysql', 'mysqldump', 'pg_dump', 'pg_restore', 'sqlite3'] as $binary) {
    Configure::write('DatabaseBackup.binaries.' . $binary, null);
}

Plugin::load('Assets', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'assets' . DS,
]);

Plugin::load('DatabaseBackup', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'cakephp-database-backup' . DS,
]);

Plugin::load('MeTools', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'me-tools' . DS,
]);

Plugin::load('Recaptcha', [
    'path' => VENDOR . 'crabstudio' . DS . 'recaptcha' . DS,
]);

Plugin::load('RecaptchaMailhide', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'cakephp-recaptcha-mailhide' . DS,
    'routes' => true,
]);

if (!getenv('THUMBER_DRIVER')) {
    putenv('THUMBER_DRIVER=imagick');
}

Configure::write('Thumber.driver', getenv('THUMBER_DRIVER'));

Plugin::load('Thumber', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'cakephp-thumber' . DS,
    'routes' => true,
]);

Plugin::load('Tokens', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'cakephp-tokens' . DS,
]);

Plugin::load('MeCms', [
    'bootstrap' => true,
    'path' => VENDOR . 'mirko-pagliai' . DS . 'me-cms' . DS,
    'routes' => true,
]);

Plugin::load('MeCmsInstagram', [
    'bootstrap' => true,
    'path' => ROOT,
    'routes' => true,
]);

$_SERVER['PHP_SELF'] = '/';
