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
 */
$this->extend('MeCms./common/index');
$this->assign('title', $title = __d('me_cms_instagram', 'Photos from {0}', 'Instagram'));

$this->Asset->script('MeCmsInstagram.instagram', ['block' => 'script_bottom']);

if (getConfig('default.fancybox')) {
    $this->Library->fancybox();
}

if (getConfig('default.user_profile') && !$this->request->is('ajax')) {
    echo $this->element('user');
}

/**
 * Breadcrumb
 */
$this->Breadcrumbs->add($title, ['_name' => 'instagramPhotos']);

//If Fancybox is enabled
$linkOptions = [];
if (getConfig('default.fancybox')) {
    $linkOptions = ['class' => 'fancybox', 'rel' => 'fancybox-group'];
}
?>

<div class="row">
<?php
foreach ($photos as $photo) {
    $link = getConfig('default.open_on_instagram') ? $photo->link : ['_name' => 'instagramPhoto', $photo->id];

    //If Fancybox is enabled, adds some options
    if (getConfig('default.fancybox')) {
        $linkOptions['data-fancybox-href'] = $this->Thumb->resizeUrl($photo->path, ['height' => 1280]);
    }

    echo $this->Html->div('col-md-4 col-lg-3 mb-4', $this->element('MeCms.views/photo-preview', [
        'path' => $photo->path,
        'text' => $photo->description,
    ] + compact('link', 'linkOptions')));
}
?>
</div>

<div class="mb-4 text-center">
<?php
if (empty($nextId)) {
    echo $this->Html->button(
        __d('me_cms_instagram', 'Follow me on {0}', 'Instagram'),
        sprintf('//instagram.com/%s', $user->username),
        ['class' => 'btn-primary btn-lg', 'icon' => 'fab instagram', 'target' => '_blank']
    );
} else {
    echo $this->Html->button(__d('me_cms_instagram', 'Load more'), '#', [
        'id' => 'load-more',
        'class' => 'btn-primary btn-lg',
        'data-href' => $this->Url->build(['_name' => 'instagramPhotosId', $nextId]),
    ]);
}
?>
</div>