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
$this->Asset->css('MeCmsInstagram.instagram', ['block' => 'css_bottom']);
?>

<?php if (getConfig('default.user_profile') && !empty($user)) : ?>
    <div id="instagram-user" class="row">
        <div class="col-sm-5">
            <?= $this->Html->img($user->profile_picture, ['id' => 'picture']) ?>
        </div>

        <div class="col-sm-7">
            <p id="username"><?= $user->username ?></p>

            <p>
                <span id="fullname"><?= $user->full_name ?></span>

                <?php if (!empty($user->bio)) : ?>
                    <span id="bio"><?= $user->bio ?></span>
                <?php endif; ?>

                <?php if (!empty($user->website)) : ?>
                    <span id="website">
                        <?= $this->Html->link(preg_replace('/^http:\/\//', '', $user->website), $user->website, ['target' => '_blank']) ?>
                    </span>
                <?php endif; ?>
            </p>

            <p id="counts">
                <span>
                    <?= __d('me_cms_instagram', '{0} posts', $this->Html->strong($user->counts->media)) ?>
                </span>
                <span>
                    <?= __d('me_cms_instagram', '{0} followers', $this->Html->strong($user->counts->followed_by)) ?>
                </span>
                <span>
                    <?= __d('me_cms_instagram', '{0} following', $this->Html->strong($user->counts->follows)) ?>
                </span>
            </p>

            <?php
            if (getConfig('default.follow_me')) {
                echo $this->Html->button(
                    __d('me_cms_instagram', 'Follow me on {0}', 'Instagram'),
                    sprintf('//instagram.com/%s', $user->username),
                    ['class' => 'btn-lg btn-success', 'icon' => 'instagram']
                );
            }
            ?>
        </div>
    </div>
<?php endif; ?>