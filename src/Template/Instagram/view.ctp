<?php
/**
 * This file is part of MeInstagram.
 *
 * MeInstagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeInstagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeInstagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 */
?>

<?php
    $this->extend('MeCms./Common/index');
    $this->assign('title', $title = $photo->filename);
    
	if(config('default.user_profile')) {
		echo $this->element('user');
    }
    
    /**
     * Breadcrumb
     */
    $this->Breadcrumb->add(__d('me_instagram', 'Photos from {0}', 'Instagram'), ['_name' => 'instagram_photos']);
    $this->Breadcrumb->add($title, ['_name' => 'instagram_photo', $photo->id]);
?>

<?= $this->Html->img($photo->path) ?>