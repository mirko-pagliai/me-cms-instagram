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
namespace MeInstagram\Utility;

use MeTools\Utility\Xml;

/**
 * An utility to get media from Instagram.
 * 
 * You can use this utility by adding:
 * <code>
 * use MeInstagram\Utility\Instagram;
 * </code>
 */
class Instagram {
	/**
	 * Gets a media from Instagram
	 * @param string $id Media ID
	 * @return array
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function getMedia($id) {
		//See https://www.instagram.com/developer/endpoints/media/#get_media
		$url = 'https://api.instagram.com/v1/media/%s?access_token=%s';
		$photo = Xml::fromFile(sprintf($url, $id, config('Instagram.key')));
		
		return (object) [
			'path' => $photo['data']['images']['standard_resolution']['url']
		];
	}

	/**
	 * Gets the recent media for an user from Instagram
	 * @return array
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function getRecentUser() {
		//See https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
		$url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=%s';
		$photos = Xml::fromFile(sprintf($url, config('Instagram.key')));
		
		return array_map(function($photo) {
			return (object) [
				'id'			=> $photo['id'],
				'description'	=> $photo['caption']['text'],
				'path'			=> $photo['images']['standard_resolution']['url']
			];
		}, $photos['data']);
	}
}