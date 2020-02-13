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
 * @since       1.5.0
 */

namespace MeCmsInstagram\Controller\Component;

use Cake\Controller\Component;
use MeCmsInstagram\InstagramTrait;

/**
 * A component to get media from Instagram
 */
class InstagramComponent extends Component
{
    use InstagramTrait;

    /**
     * Constructor hook method
     * @param array $config The configuration settings provided to this component
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->key = $config['key'] ?? getConfigOrFail('Instagram.key');
    }
}
