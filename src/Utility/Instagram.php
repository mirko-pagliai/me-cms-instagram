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

namespace MeCmsInstagram\Utility;

use MeCmsInstagram\InstagramTrait;

/**
 * An utility to get media from Instagram
 */
class Instagram
{
    use InstagramTrait;

    /**
     * Construct
     * @param string|null $key API access token
     */
    public function __construct($key = null)
    {
        $this->key = $key ?: getConfigOrFail('Instagram.key');
    }
}
