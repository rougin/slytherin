<?php

namespace Rougin\Slytherin\Debug\Whoops;

use Rougin\Slytherin\Debug\Whoops\Debugger;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;
use Whoops\Handler\PrettyPageHandler;

/**
 * Whoops Debugger Test
 *
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
     * Sets up the debugger.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('Whoops\Run'))
        {
            $this->markTestSkipped('Whoops is not installed.');
        }

        $this->debugger = new Debugger(new \Whoops\Run);
    }

    /**
     * Tests if the debugger's environment is equal to the specified environment.
     *
     * @return void
     */
    public function testSetEnvironmentMethod()
    {
        $this->debugger->setEnvironment($this->environment);

        $expected = $this->environment;

        $actual = $this->debugger->getEnvironment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests if the specified handler is in the debugger's list of handlers.
     *
     * @return void
     */
    public function testSetHandlerMethod()
    {
        $this->debugger->setHandler(new PrettyPageHandler);

        $handlers = $this->debugger->getHandlers();

        $expected = 'Whoops\Handler\PrettyPageHandler';

        $actual = $handlers[0];

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests if the specified handler is in the debugger's list of handlers.
     *
     * @return void
     */
    public function testSetHandlerMethodWithCallback()
    {
        $fn = function () { return 'Hello'; };

        $this->debugger->setHandler($fn);

        $handlers = $this->debugger->getHandlers();

        $expected = 'Whoops\Handler\CallbackHandler';

        $actual = $handlers[0];

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->debugger->display();

        $this->assertEquals(error_reporting(), E_ALL);
    }

    /**
     * Tests if the debugger is implemented in DebuggerInterface.
     *
     * @return void
     */
    public function testDebuggerInterface()
    {
        $this->assertInstanceOf(System::DEBUGGER, $this->debugger);
    }
}
