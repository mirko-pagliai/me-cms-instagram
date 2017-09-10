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
$this->extend('MeCms./Common/index');
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

//Sets base options for each photo
$baseOptions = ['class' => 'd-block'];

//If Fancybox is enabled
if (getConfig('default.fancybox')) {
    $baseOptions = ['class' => 'd-block fancybox', 'rel' => 'fancybox-group'];
}
?>

<div class="row">
    <?php foreach ($photos as $photo) : ?>
        <?php
        if (getConfig('default.open_on_instagram')) {
            $link = $photo->link;
        } else {
            $link = $this->Url->build(['_name' => 'instagramPhoto', $photo->id]);
        }
        $options = $baseOptions + ['title' => $photo->description];

        //If Fancybox is enabled, adds some options
        if (getConfig('default.fancybox')) {
            $options += ['data-fancybox-href' => $this->Thumb->resizeUrl($photo->path, ['height' => 1280])];
        }
        ?>

        <div class="col-md-4 col-lg-3 mb-4">
            <a href="<?= $link ?>" <?= toAttributes($options) ?>>
                <div class="card border-0 text-white">
                    <?= $this->Thumb->fit($photo->path, ['width' => 275], ['class' => 'card-img rounded-0']) ?>
                    <div class="card-img-overlay card-img-overlay-transition p-3">
                        <p class="card-text small"><?= $this->Text->truncate($photo->description, 150) ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php if (!empty($nextId)) : ?>
    <div class="mb-3 text-center">
        <?= $this->Html->link(__d('me_cms_instagram', 'Load more'), '#', [
            'id' => 'load-more',
            'class' => 'primary lg',
            'data-href' => $this->Url->build(['_name' => 'instagramPhotosId', $nextId]),
        ]) ?>
    </div>
<?php endif; ?>