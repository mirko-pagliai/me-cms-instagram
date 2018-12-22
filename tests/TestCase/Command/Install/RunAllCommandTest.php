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
namespace MeCmsInstagram\Test\TestCase\Command\Install;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use MeCms\TestSuite\TestCase;
use MeTools\Console\Command;
use MeTools\TestSuite\ConsoleIntegrationTestTrait;

/**
 * RunAllCommandTest class
 */
class RunAllCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait {
        ConsoleIntegrationTestTrait::setUp as consoleSetUp;
    }

    /**
     * If `true`, a mock instance of the shell will be created
     * @var bool
     */
    protected $autoInitializeClass = true;

    /**
     * @var array
     */
    protected $debug = [];

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        $this->consoleSetUp();

        $this->loadPlugins(['MeCms', 'MeCmsInstagram']);
    }

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute()
    {
        $io = $this->getMockBuilder(ConsoleIo::class)
            ->setMethods(['askChoice'])
            ->getMock();

        $io->method('askChoice')->will($this->returnValue('y'));

        $this->Command->questions = array_map(function ($question) {
            $command = $this->getMockBuilder(Command::class)
                ->setMethods(['execute'])
                ->getMock();
            $command->method('execute')->will($this->returnCallback(function () use ($question) {
                $this->debug[] = $question['command'];
            }));
            $question['command'] = $command;

            return $question;
        }, $this->Command->questions);

        $expected = ['MeCmsInstagram\Command\Install\CopyConfigCommand'];
        $this->Command->execute(new Arguments([], ['force' => true], []), $io);
        $this->assertEquals($expected, $this->debug);

        $this->debug = [];
        $this->Command->execute(new Arguments([], [], []), $io);
        $this->assertEquals($expected, $this->debug);
    }
}
