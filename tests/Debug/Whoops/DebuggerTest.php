<?php

namespace Rougin\Slytherin\Debug\Whoops;

/**
 * Whoops Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Debug\DebuggerInterface
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
    public function setUp()
    {
        if (! class_exists('Whoops\Run')) {
            $this->markTestSkipped('Whoops is not installed.');
        }

        $this->debugger = new \Rougin\Slytherin\Debug\Whoops\Debugger(new \Whoops\Run);
    }

    /**
     * Tests if the debugger's environment is equal to the specified environment.
     *
     * @return void
     */
    public function testSetEnvironmentMethod()
    {
        $this->debugger->setEnvironment($this->environment);

        $this->assertEquals($this->environment, $this->debugger->getEnvironment());
    }

    /**
     * Tests if the specified handler is in the debugger's list of handlers.
     *
     * @return void
     */
    public function testSetHandlerMethod()
    {
        $this->debugger->setHandler(new \Whoops\Handler\PrettyPageHandler);

        $handler = $this->debugger->getHandlers()[0];

        $this->assertInstanceOf('Whoops\Handler\PrettyPageHandler', $handler);
    }

    /**
     * Tests if the specified handler is in the debugger's list of handlers.
     *
     * @return void
     */
    public function testSetHandlerMethodWithCallback()
    {
        $this->debugger->setHandler(function () {
            return 'Hello';
        });

        $handler = $this->debugger->getHandlers()[0];

        $this->assertInstanceOf('Whoops\Handler\CallbackHandler', $handler);
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->assertInstanceOf('Whoops\Run', $this->debugger->display());
    }

    /**
     * Tests if the debugger is implemented in DebuggerInterface.
     *
     * @return void
     */
    public function testDebuggerInterface()
    {
        $interface = 'Rougin\Slytherin\Debug\DebuggerInterface';

        $this->assertInstanceOf($interface, $this->debugger);
    }
}
