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
if (empty($photos)) {
    return;
}

$this->extend('MeCms./Common/widget');
$this->assign('title', __dn('me_cms', 'Latest photo', 'Latest {0} photos', count($photos), count($photos)));

foreach ($photos as $photo) {
    echo $this->Html->link(
        $this->Thumb->fit($photo->path, ['width' => 253]),
        ['_name' => 'instagramPhotos'],
        ['class' => 'thumbnail', 'title' => $photo->description]
    );
}
