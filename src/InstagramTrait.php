<?php
declare(strict_types=1);
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
 * @since       1.5.0
 */
namespace MeCmsInstagram;

use Cake\Http\Client;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
use stdClass;

/**
 * A trait that provides methods for getting media from Instagram.
 *
 * Remember that you should set the `$key` property (the API access token)
 *  somewhere, for example in the `__construct()` method. Otherwise, the
 *  `getKey()` method will automatically return the configuration value.
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
    protected function getClient(): Client
    {
        return new Client();
    }

    /**
     * Internal method to get the key
     * @return string
     */
    protected function getKey(): string
    {
        return $this->key ?: getConfigOrFail('Instagram.key');
    }

    /**
     * Internal method to get a media response
     * @param string $mediaId Media ID
     * @return string The response body as string
     * @uses getClient()
     * @uses $key
     */
    protected function getMediaResponse(string $mediaId): string
    {
        $url = 'https://api.instagram.com/v1/media/' . $mediaId . '?access_token=' . $this->key;

        return $this->getClient()->get($url)->getStringBody();
    }

    /**
     * Internal method to get a "recent" response
     * @param string|null $requestId Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return string The response body as string
     * @uses getClient()
     * @uses $key
     */
    protected function getRecentResponse(?string $requestId = null, int $limit = 15): string
    {
        $url = 'https://api.instagram.com/v1/users/self/media/recent/?count=' . $limit . '&access_token=' . $this->key;

        //Adds the request ID ("Next ID" for Istangram) to the url
        if ($requestId) {
            $url .= '&max_id=' . $requestId;
        }

        return $this->getClient()->get($url)->getStringBody();
    }

    /**
     * Internal method to get an user response
     * @return string The response body as string
     * @uses getClient()
     * @uses $key
     */
    protected function getUserResponse(): string
    {
        $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $this->key;

        return $this->getClient()->get($url)->getStringBody();
    }

    /**
     * Gets a media object
     * @param string $id Media ID
     * @return \Cake\ORM\Entity
     * @see https://www.instagram.com/developer/endpoints/media/#get_media
     * @throws \Cake\Http\Exception\NotFoundException
     * @uses getMediaResponse()
     */
    public function media(string $id): Entity
    {
        $photo = json_decode($this->getMediaResponse($id));
        $path = $photo->data->images->standard_resolution->url ?? null;
        is_true_or_fail($path, I18N_NOT_FOUND, NotFoundException::class);
        $filename = array_value_first(explode('?', basename($path), 2));

        return new Entity(compact('id', 'filename', 'path'));
    }

    /**
     * Gets the most recent media published by the owner of token
     * @param string|null $requestId Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return array Array with entities of photos and "Next ID"
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
     * @uses getRecentResponse()
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function recent(?string $requestId = null, int $limit = 15): array
    {
        $photos = json_decode($this->getRecentResponse($requestId, $limit));
        is_true_or_fail(isset($photos->data), $photos->meta->error_message ?? I18N_NOT_FOUND, NotFoundException::class);

        $nextId = $photos->pagination->next_max_id ?? null;

        $photos = collection($photos->data)
            ->take($limit)
            ->map(function (stdClass $photo) {
                $path = $photo->images->standard_resolution->url;

                return new Entity(compact('path') + [
                    'id' => $photo->id,
                    'link' => $photo->link,
                    'filename' => array_value_first(explode('?', basename($path), 2)),
                    'description' => $photo->caption->text ?? null,
                ]);
            })
            ->toList();

        return [$photos, $nextId];
    }

    /**
     * Gets information about the owner of the token
     * @return \Cake\ORM\Entity
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_self
     * @throws \Cake\Http\Exception\NotFoundException
     * @uses getUserResponse()
     */
    public function user(): Entity
    {
        $user = json_decode($this->getUserResponse());
        is_true_or_fail(isset($user->data), I18N_NOT_FOUND, NotFoundException::class);
        $user->data->counts = new Entity((array)$user->data->counts);

        return new Entity((array)$user->data);
    }
}
