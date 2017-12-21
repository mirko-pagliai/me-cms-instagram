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
namespace MeCmsInstagram\Shell;

use Cake\Console\ConsoleIo;
use MeCms\Shell\InstallShell as BaseInstallShell;

/**
 * Executes some tasks to make the system ready to work
 */
class InstallShell extends BaseInstallShell
{
    /**
     * Construct
     * @param \Cake\Console\ConsoleIo|null $io An io instance
     * @uses $config
     * @uses $questions
     * @uses MeCms\Shell\InstallShell::__construct()
     */
    public function __construct(ConsoleIo $io = null)
    {
        parent::__construct($io);

        //Configuration files to be copied
        $this->config = [
            'MeCmsInstagram.me_cms_instagram',
        ];

        //Questions used by `all()` method
        $this->questions = [
            [
                'question' => __d('me_tools', 'Copy configuration files?'),
                'default' => 'Y',
                'method' => 'copyConfig',
            ],
        ];
    }

    /**
     * Gets the option parser instance and configures it.
     * @return ConsoleOptionParser
     * @uses MeCms\Shell\InstallShell::getOptionParser()
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        //Resets subcommands
        foreach (array_keys($parser->toArray()['subcommands']) as $subcommand) {
            $parser->removeSubcommand($subcommand);
        }

        $parser->addSubcommand('all', ['help' => __d('me_tools', 'Executes all available tasks')]);
        $parser->addSubcommand('copyConfig', ['help' => __d('me_tools', 'Copies the configuration files')]);

        return $parser;
    }
}
