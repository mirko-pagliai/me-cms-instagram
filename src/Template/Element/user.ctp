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
?>
<?php if (getConfig('default.user_profile') && !empty($user)) : ?>
    <div class="card bg-light border-light mb-4">
        <div class="card-body row">
            <div class="col-5 text-center">
                <?= $this->Html->img($user->profile_picture, ['class' => 'rounded-circle']) ?>
            </div>

            <div class="col">
                <h3 class="card-title">
                    <?= $user->username ?>
                </h3>

                <p>
                    <strong class="mr-2"><?= $user->full_name ?></strong>

                    <?php if ($user->has('bio')) : ?>
                        <span class="mr-2"><?= $user->bio ?></span>
                    <?php endif; ?>

                    <?php if ($user->has('website')) : ?>
                        <span>
                            <?= $this->Html->link(
                                preg_replace(['/^http:\/\//', '/\/$/'], '', $user->website),
                                $user->website,
                                ['target' => '_blank']
                            ) ?>
                        </span>
                    <?php endif; ?>
                </p>

                <p>
                    <span class="mr-3">
                        <?= __d('me_cms_instagram', '{0} posts', $this->Html->strong($user->counts->media)) ?>
                    </span>
                    <span class="mr-3">
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
                        ['class' => 'btn-success btn-sm', 'icon' => 'instagram', 'target' => '_blank']
                    );
                }
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>