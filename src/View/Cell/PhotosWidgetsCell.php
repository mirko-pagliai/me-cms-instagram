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
 */
namespace MeCmsInstagram\View\Cell;

use Cake\Cache\Cache;
use Cake\View\Cell;
use MeCmsInstagram\Utility\Instagram;

/**
 * PhotosWidgets cell
 */
class PhotosWidgetsCell extends Cell
{
    /**
     * @var \MeCmsInstagram\Utility\Instagram
     */
    public $Instagram;

    /**
     * Initialization hook method
     * @return void
     * @uses $Instagram
     */
    public function initialize(): void
    {
        $this->Instagram = new Instagram();
    }

    /**
     * Internal method to get the latest photos
     * @param int $limit Limit
     * @return array
     * @uses \MeCmsInstagram\Utility\Instagram::recent()
     * @uses $Instagram
     */
    protected function getLatest(int $limit = 12): array
    {
        return Cache::remember(sprintf('widget_latest_%s', $limit), function () use ($limit) {
            return array_value_first($this->Instagram->recent(null, $limit));
        }, 'instagram');
    }

    /**
     * Latest widget
     * @param int $limit Limit
     * @return void
     * @uses getLatest()
     */
    public function latest(int $limit = 1): void
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $this->set('photos', $this->getLatest($limit));
    }

    /**
     * Random widget
     * @param int $limit Limit
     * @return void
     * @uses getLatest()
     */
    public function random(int $limit = 1): void
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $this->set('photos', collection($this->getLatest())->sample($limit)->toArray());
    }
}
