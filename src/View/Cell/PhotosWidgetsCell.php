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
namespace MeCmsInstagram\View\Cell;

use Cake\Cache\Cache;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\Network\Response;
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
     * Constructor
     * @param \Cake\Http\ServerRequest|null $request The request to use in the cell
     * @param \Cake\Http\Response|null $response The response to use in the cell
     * @param \Cake\Event\EventManager|null $eventManager The eventManager to bind events to
     * @param array $cellOptions Cell options to apply
     * @since 1.5.0
     */
    public function __construct(
        ServerRequest $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $cellOptions = []
    ) {
        parent::__construct($request, $response, $eventManager, $cellOptions);

        $this->Instagram = new Instagram;
    }

    /**
     * Internal method to get the latest photos
     * @param int $limit Limit
     * @return array
     * @uses MeCmsInstagram\Utility\Instagram::recent()
     * @uses $Instagram
     */
    protected function getLatest($limit = 12)
    {
        return Cache::remember(sprintf('widget_latest_%s', $limit), function () use ($limit) {
            return first_value($this->Instagram->recent(null, $limit));
        }, 'instagram');
    }

    /**
     * Latest widget
     * @param int $limit Limit
     * @return void
     * @uses getLatest()
     */
    public function latest($limit = 1)
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $photos = $this->getLatest($limit);

        $this->set(compact('photos'));
    }

    /**
     * Random widget
     * @param int $limit Limit
     * @return void
     * @uses getLatest()
     */
    public function random($limit = 1)
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $photos = collection($this->getLatest())->sample($limit)->toArray();

        $this->set(compact('photos'));
    }
}
