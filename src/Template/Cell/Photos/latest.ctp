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
	//Returns on the same controllers
	if($this->request->isController('Instagram'))
		return;
?>

<?php if(!empty($photos)): ?>
	<div class="widget sidebar-widget">
		<?php			
			echo $this->Html->h4(count($photos) > 1 ? __d('me_cms', 'Latest {0} photos', count($photos)) : __d('me_cms', 'Latest photo'));
			
			foreach($photos as $photo)
				echo $this->Html->link($this->Thumb->img($photo->path, ['side' => 253]), ['_name' => 'instagram'], ['class' => 'thumbnail']);
		?>
	</div>
<?php endif; ?>