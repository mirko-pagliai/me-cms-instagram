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
 * @since       1.5.0
 */
namespace MeCmsInstagram;

use Cake\Http\Client;
use Cake\Network\Exception\NotFoundException;

/**
 * A trait that provides methods for getting media from Instagram.
 *
 * Remember that you should set the `$key` property (the API access token)
 *  somewhere, for example in the `__construct()` method. Otherwise, the
 *  `_getKey()` method will automatically return the configuration value.
 */
trait InstagramTrait
{
    /**
     * API access token
     * @var string
     */
    protected $key;

    /**
     * Internal method to get a `Client` instance
     * @return \Cake\Http\Client
     */
    protected function _getClient()
    {
        return new Client;
    }

    /**
     * Internal method to get the key
     * @return string
     */
    protected function _getKey()
    {
        if (!$this->key) {
            return getConfig('Instagram.key');
        }

        return $this->key;
    }

    /**
     * Internal method to get a media response
     * @param string $id Media ID
     * @return mixed The response body
     * @uses _getClient()
     * @uses $key
     */
    protected function _getMediaResponse($id)
    {
        $url = 'https://api.instagram.com/v1/media/' . $id . '?access_token=' . $this->key;

        return $this->_getClient()->get($url)->body;
    }

    /**
     * Internal method to get a "recent" response
     * @param string $id Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return mixed The response body
     * @uses _getClient()
     * @uses $key
     */
    protected function _getRecentResponse($id = null, $limit = 15)
    {
        $url = 'https://api.instagram.com/v1/users/self/media/recent/?count=' . $limit . '&access_token= ' . $this->key;

        //Adds the request ID ("Next ID" for Istangram) to the url
        if (!empty($id)) {
            $url .= '&max_id=' . $id;
        }

        return $this->_getClient()->get($url)->body;
    }

    /**
     * Internal method to get an user response
     * @return mixed The response body
     * @uses _getClient()
     * @uses $key
     */
    protected function _getUserResponse()
    {
        $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $this->key;

        return $this->_getClient()->get($url)->body;
    }

    /**
     * Gets a media object
     * @param string $id Media ID
     * @return object
     * @see https://www.instagram.com/developer/endpoints/media/#get_media
     * @throws NotFoundException
     * @uses _getMediaResponse()
     */
    public function media($id)
    {
        $photo = json_decode($this->_getMediaResponse($id));

        if (empty($photo->data->images->standard_resolution->url)) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        $path = $photo->data->images->standard_resolution->url;

        return (object)array_merge(compact('id', 'path'), [
            'filename' => explode('?', basename($path), 2)[0],
        ]);
    }

    /**
     * Gets the most recent media published by the owner of token
     * @param string $id Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return array Array with photos and "Next ID"
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
     * @uses _getRecentResponse()
     * @throws NotFoundException
     */
    public function recent($id = null, $limit = 15)
    {
        $photos = json_decode($this->_getRecentResponse($id, $limit));

        if (empty($photos->data)) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        $nextId = empty($photos->pagination->next_max_id) ? null : $photos->pagination->next_max_id;

        $photos = collection($photos->data)->take($limit)->map(function ($photo) {
            $path = $photo->images->standard_resolution->url;

            return (object)array_merge(compact('path'), [
                'id' => $photo->id,
                'link' => $photo->link,
                'filename' => explode('?', basename($path), 2)[0],
                'description' => empty($photo->caption->text) ? null : $photo->caption->text,
            ]);
        })->toList();

        return [$photos, $nextId];
    }

    /**
     * Gets information about the owner of the token
     * @return object
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_self
     * @throws NotFoundException
     * @uses _getUserResponse()
     */
    public function user()
    {
        $user = json_decode($this->_getUserResponse());

        if (empty($user->data)) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        return $user->data;
    }
}
