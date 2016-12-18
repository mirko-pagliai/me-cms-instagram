<?php
/**
 * This file is part of me-cms-instagram.
 *
 * me-cms-instagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * me-cms-instagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with me-cms-instagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */

$this->extend('MeCms./Common/index');
$this->assign('title', $title = __d('me_cms_instagram', 'Photos from {0}', 'Instagram'));

$this->Asset->script('MeCmsInstagram.instagram', ['block' => 'script_bottom']);

if (config('default.fancybox')) {
    $this->Library->fancybox();
}

if (config('default.user_profile') && !$this->request->is('ajax')) {
    echo $this->element('user');
}

/**
 * Breadcrumb
 */
$this->Breadcrumbs->add($title, ['_name' => 'instagramPhotos']);
?>

<div class="photosAlbums index">
    <div class="clearfix">
        <?php foreach ($photos as $photo) : ?>
            <div class="col-sm-6 col-md-4">
                <div class="photo-box">
                    <?php
                    $text = implode(PHP_EOL, [
                        $this->Thumb->fit($photo->path, ['width' => 275]),
                        $this->Html->div(
                            'photo-info',
                            $this->Html->div(null, $this->Html->para('small', $this->Text->truncate($photo->description, 350)))
                        ),
                    ]);

                    if (config('default.open_on_instagram')) {
                        $link = $photo->link;
                    } else {
                        $link = ['_name' => 'instagramPhoto', $photo->id];
                    }

                    $options = [
                        'class' => 'thumbnail',
                        'title' => $photo->description,
                    ];

                    //If Fancybox is enabled, adds some options
                    if (config('default.fancybox')) {
                        $options['class'] = 'fancybox thumbnail';
                        $options['data-fancybox-href'] = $this->Thumb->resizeUrl($photo->path, ['height' => 1280]);
                        $options['rel'] = 'group';
                    }

                    echo $this->Html->link($text, $link, $options);
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    if (!empty($nextId)) {
        echo $this->Html->link(__d('me_cms_instagram', 'Load more'), '#', [
            'id' => 'load-more',
            'data-href' => $this->Url->build(['_name' => 'instagramPhotosId', $nextId]),
        ]);
    }
    ?>
</div>