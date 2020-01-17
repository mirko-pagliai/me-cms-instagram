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

namespace MeCmsInstagram\Test\TestCase;

use Cake\I18n\I18n;
use MeTools\TestSuite\TestCase;

/**
 * I18nTest class
 */
class I18nTest extends TestCase
{
    /**
     * Tests I18n translations
     * @test
     */
    public function testI18nConstant()
    {
        $translator = I18n::getTranslator('me_cms_instagram', 'it');
        $this->assertEquals('Carica altro', $translator->translate('Load more'));
    }
}
