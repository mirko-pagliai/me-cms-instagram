<?php
declare(strict_types=1);

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
 * @since       1.9.0
 */

namespace MeCmsInstagram\Command\Install;

use MeCmsInstagram\Command\Install\CopyConfigCommand;
use MeTools\Command\Install\RunAllCommand as BaseRunAllCommand;

/**
 * Copies the configuration files
 */
class RunAllCommand extends BaseRunAllCommand
{
    /**
     * Constructor
     * @uses $questions
     */
    public function __construct()
    {
        parent::__construct();

        $this->questions = [
            [
                'question' => __d('me_tools', 'Copy configuration files?'),
                'default' => 'Y',
                'command' => CopyConfigCommand::class,
            ],
        ];
    }
}
