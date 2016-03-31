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

use Cake\Routing\Router;
?>
	
<?php
	$this->assign('title', __d('me_instagram', 'Photos from {0}', 'Instagram'));
	
	$this->Asset->js('MeInstagram.frontend', ['block' => 'script_bottom']);
	
	if(config('frontend.fancybox'))
		$this->Library->fancybox();
	
	if(config('frontend.user_profile') && !$this->request->is('ajax'))
		echo $this->element('frontend/user');
?>

<div class="photosAlbums index">
	<div class="clearfix">
		<?php foreach($photos as $photo): ?>
			<div class="col-sm-6 col-md-4">
				<div class="photo-box">
					<?php
						$text = implode(PHP_EOL, [
							$this->Thumb->image($photo->path, ['side' => 275]),
							$this->Html->div('photo-info', $this->Html->div(NULL, $this->Html->para('small', $photo->description)))
						]);
						
						$link = config('frontend.open_on_instagram') ? $photo->link : ['action' => 'view', $photo->id];
						
						//If Fancybox is enabled, adds some options
						$options = config('frontend.fancybox') ? [
							'class'					=> 'fancybox thumbnail',
							'data-fancybox-href'	=> $this->Thumb->url($photo->path, ['height' => 1280]),
							'rel'					=> 'group'
						] : [];
						
						echo $this->Html->link($text, $link, am([
							'class' => 'thumbnail',
							'title' => $photo->description
						], $options));
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
    
	<?php if(!empty($next_id)): ?>
		<?= $this->Html->link(__d('me_instagram', 'Load more'), '#', ['id' => 'load-more', 'data-href' => Router::url([$next_id])]) ?>
	<?php endif; ?>
</div>