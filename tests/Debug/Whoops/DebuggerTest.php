<?php

namespace Rougin\Slytherin\Debug\Whoops;

/**
 * Whoops Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends \LegacyPHPUnit\TestCase
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
    protected function doSetUp()
    {
        if (! class_exists('Whoops\Run')) {
            $this->markTestSkipped('Whoops is not installed.');
        }

        $whoops = new \Whoops\Run;

        $this->debugger = new \Rougin\Slytherin\Debug\Whoops\Debugger($whoops);
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

        $handlers = $this->debugger->getHandlers();

        $this->assertInstanceOf('Whoops\Handler\PrettyPageHandler', $handlers[0]);
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

        $handlers = $this->debugger->getHandlers();

        $this->assertInstanceOf('Whoops\Handler\CallbackHandler', $handlers[0]);
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
     * Tests if the debugger is implemented in ErrorHandlerInterface.
     *
     * @return void
     */
    public function testDebuggerInterface()
    {
        $interface = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

        $this->assertInstanceOf($interface, $this->debugger);
    }
}
