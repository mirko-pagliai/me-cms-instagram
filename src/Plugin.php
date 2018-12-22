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
 * @since       1.8.0
 */
namespace MeCmsInstagram;

use Cake\Core\BasePlugin;
use MeCmsInstagram\Command\Install\CopyConfigCommand;
use MeCmsInstagram\Command\Install\RunAllCommand;

/**
 * Plugin class
 */
class Plugin extends BasePlugin
{
    /**
     * Add console commands for the plugin
     * @param Cake\Console\CommandCollection $commands The command collection to update
     * @return Cake\Console\CommandCollection
     * @since 1.9.0
     */
    public function console($commands)
    {
        $commands->add('me_cms_instagram.copy_config', CopyConfigCommand::class);
        $commands->add('me_cms_instagram.install', RunAllCommand::class);

        return $commands;
    }
}
