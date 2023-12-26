<?php

namespace Rougin\Slytherin\Debug\Whoops;

use Rougin\Slytherin\Debug\Whoops\Debugger;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;
use Whoops\Handler\PrettyPageHandler;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Debug\Whoops\Debugger
     */
    protected $debugger;

    /**
     * @var string
     */
    protected $environment = 'production';

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Whoops\Run'))
        {
            $this->markTestSkipped('Whoops is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->debugger = new Debugger(new \Whoops\Run);
    }

    /**
     * @return void
     */
    public function test_setting_the_environment()
    {
        $this->debugger->setEnvironment($this->environment);

        $expected = $this->environment;

        $actual = $this->debugger->getEnvironment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_handler()
    {
        $this->debugger->setHandler(new PrettyPageHandler);

        $handlers = $this->debugger->getHandlers();

        $expected = 'Whoops\Handler\PrettyPageHandler';

        $actual = $handlers[0];

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_handler_as_a_callback()
    {
        $fn = function () { return 'Hello'; };

        $this->debugger->setHandler($fn);

        $handlers = $this->debugger->getHandlers();

        $expected = 'Whoops\Handler\CallbackHandler';

        $actual = $handlers[0];

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_error_reporting()
    {
        $this->debugger->display();

        $expected = E_ALL;

        $actual = error_reporting();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_debugger_instance()
    {
        $this->assertInstanceOf(System::DEBUGGER, $this->debugger);
    }
}
