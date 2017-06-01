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
namespace MeCmsInstagram\Utility;

use Cake\Http\Client;
use Cake\Network\Exception\NotFoundException;

/**
 * An utility to get media from Instagram
 */
class Instagram
{
    /**
     * @var Cake\Http\Client
     */
    protected $Client;

    /**
     * API access token
     * @var string
     */
    protected $key;

    /**
     * Construct
     * @param string $key API access token
     * @uses $Client
     * @uses $key
     */
    public function __construct($key = null)
    {
        $this->Client = new Client;

        if (empty($key)) {
            $key = getConfig('Instagram.key');
        }

        $this->key = $key;
    }

    /**
     * Internal method to get a media response
     * @param string $id Media ID
     * @return mixed The response body
     * @uses $Client
     * @uses $key
     */
    protected function _getMediaResponse($id)
    {
        $url = 'https://api.instagram.com/v1/media/' . $id . '?access_token=' . $this->key;

        return $this->Client->get($url)->body;
    }

    /**
     * Internal method to get a "recent" response
     * @param string $id Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return mixed The response body
     * @uses $Client
     * @uses $key
     */
    protected function _getRecentResponse($id = null, $limit = 15)
    {
        $url = 'https://api.instagram.com/v1/users/self/media/recent/?count=' . $limit . '&access_token= ' . $this->key;

        //Adds the request ID ("Next ID" for Istangram) to the url
        if (!empty($id)) {
            $url .= '&max_id=' . $id;
        }

        return $this->Client->get($url)->body;
    }

    /**
     * Internal method to get an user response
     * @return mixed The response body
     * @uses $Client
     * @uses $key
     */
    protected function _getUserResponse()
    {
        $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $this->key;

        return $this->Client->get($url)->body;
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

        $object = (object)compact('id');
        $object->path = $photo->data->images->standard_resolution->url;
        $object->filename = explode('?', basename($object->path), 2)[0];

        return $object;
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
            $object = new \stdClass;
            $object->id = $photo->id;
            $object->link = $photo->link;
            $object->path = $photo->images->standard_resolution->url;
            $object->filename = explode('?', basename($object->path), 2)[0];
            $object->description = empty($photo->caption->text) ? null : $photo->caption->text;

            return $object;
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
