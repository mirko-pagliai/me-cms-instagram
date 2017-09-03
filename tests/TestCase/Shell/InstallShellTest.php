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
namespace MeCmsInstagram\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\Stub\ConsoleOutput;
use MeCmsInstagram\Shell\InstallShell;
use MeTools\TestSuite\TestCase;

/**
 * InstallShellTest class
 */
class InstallShellTest extends TestCase
{
    /**
     * @var \MeCmsInstagram\Shell\InstallShell
     */
    protected $InstallShell;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $err;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $out;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->out = new ConsoleOutput;
        $this->err = new ConsoleOutput;
        $io = new ConsoleIo($this->out, $this->err);
        $io->level(2);

        $this->InstallShell = $this->getMockBuilder(InstallShell::class)
            ->setMethods(['_stop', 'copyConfig'])
            ->setConstructorArgs([$io])
            ->getMock();
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $this->assertEquals([
            'MeCmsInstagram.me_cms_instagram',
        ], $this->getProperty($this->InstallShell, 'config'));
    }

    /**
     * Test for `all()` method
     * @test
     */
    public function testAll()
    {
        $this->InstallShell->method('copyConfig')
            ->will($this->returnCallback(function () {
                $this->out->write('called `copyConfig`');
            }));

        //Calls with `force` options
        $this->InstallShell->params['force'] = true;
        $this->InstallShell->all();

        //Calls with no interactive mode
        unset($this->InstallShell->params['force']);
        $this->InstallShell->interactive = false;
        $this->InstallShell->all();

        $this->assertEquals([
            'called `copyConfig`',
            'called `copyConfig`',
        ], $this->out->messages());
        $this->assertEmpty($this->err->messages());
    }

    /**
     * Test for `getOptionParser()` method
     * @test
     */
    public function testGetOptionParser()
    {
        $parser = $this->InstallShell->getOptionParser();

        $this->assertInstanceOf('Cake\Console\ConsoleOptionParser', $parser);
        $this->assertArrayKeysEqual([
            'all',
            'copy_config',
        ], $parser->subcommands());
    }
}
