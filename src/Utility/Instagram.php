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

use Cake\Network\Exception\NotFoundException;
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
	 * Gets a media object
	 * @param string $id Media ID
	 * @return object
     * @see https://www.instagram.com/developer/endpoints/media/#get_media
     * @throws NotFoundException
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function media($id) {
        $url = sprintf('https://api.instagram.com/v1/media/%s?access_token=%s', $id, config('Instagram.key'));
        $photo = @Xml::fromFile($url);
        
        if(empty($photo['data']['images']['standard_resolution']['url']))
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        
        return (object) [
            'filename'  => explode('?', basename($photo['data']['images']['standard_resolution']['url']), 2)[0],
            'path'      => $photo['data']['images']['standard_resolution']['url'],
        ];
	}

	/**
	 * Gets the most recent media published by the owner of token
	 * @param string $id Request ID ("Next ID" for Istangram)
	 * @param int $limit Limit
	 * @return array Array with photos and "Next ID"
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
     * @throws NotFoundException
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function recent($id = NULL, $limit = 15) {
        $url = sprintf('https://api.instagram.com/v1/users/self/media/recent/?count=%s&access_token=%s', $limit, config('Instagram.key'));

        //Adds the request ID ("Next ID" for Istangram) to the url
        if(!empty($id))
            $url = sprintf('%s&max_id=%s', $url, $id);

        //Gets photos
        $photos = @Xml::fromFile($url);

        if(empty($photos['data']))
            throw new NotFoundException(__d('me_cms', 'Record not found'));

        $next_id = empty($photos['pagination']['next_max_id']) ? NULL : $photos['pagination']['next_max_id'];
        
        $photos = array_map(function($photo) {
            return (object) [
                'id'			=> $photo['id'],
                'description'	=> $photo['caption']['text'],
                'link'			=> $photo['link'],
                'path'			=> $photo['images']['standard_resolution']['url'],
            ];
        }, $photos['data']);

        return [$photos, $next_id];
	}
	
	/**
	 * Gets information about the owner of the token.
	 * @return object
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_self
     * @throws NotFoundException
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function user() {
        $url = sprintf('https://api.instagram.com/v1/users/self/?access_token=%s', config('Instagram.key'));
        $user = @Xml::fromFile($url);

        if(empty($user['data']))
            throw new NotFoundException(__d('me_cms', 'Record not found'));

        return (object) array_map(function($v) {
            return is_array($v) ? (object) $v : $v;
        }, $user['data']);
	}
}