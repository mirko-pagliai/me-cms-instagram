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
namespace MeCmsInstagram\View\Cell;

use Cake\Cache\Cache;
use Cake\View\Cell;
use MeCmsInstagram\Utility\Instagram;

/**
 * Photos cell
 */
class PhotosCell extends Cell
{
    /**
     * Internal method to get the latest photos
     * @param int $limit Limit
     * @return array
     * @uses MeCmsInstagram\Utility\Instagram::recent()
     */
    protected function _latest($limit = 15)
    {
        //Tries to get data from the cache
        $photos = Cache::read($cache = sprintf('latest_%s', $limit), 'instagram');

        //If the data are not available from the cache
        if (empty($photos)) {
            list($photos) = (new Instagram)->recent(null, $limit);

            Cache::write($cache, $photos, 'instagram');
        }

        return $photos;
    }

    /**
     * Latest widget
     * @param int $limit Limit
     * @return void
     * @uses _latest()
     */
    public function latest($limit = 1)
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $photos = $this->_latest($limit);

        $this->set(compact('photos'));
    }

    /**
     * Random widget
     * @param int $limit Limit
     * @return void
     * @uses _latest()
     */
    public function random($limit = 1)
    {
        //Returns on the same controller
        if ($this->request->isController('Instagram')) {
            return;
        }

        $photos = $this->_latest();

        //Shuffles
        shuffle($photos);

        //Extract
        $photos = array_slice($photos, 0, $limit);

        $this->set(compact('photos'));
    }
}
