<?php

namespace Rougin\Slytherin\Test\Debug;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Rougin\Slytherin\Debug\WhoopsDebugger;

use PHPUnit_Framework_TestCase;

/**
 * Whoops Debugger Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsDebuggerTest extends PHPUnit_Framework_TestCase
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
        $this->debugger = new WhoopsDebugger(new Run);
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
        $this->debugger->setHandler(new PrettyPageHandler);

        $this->assertInstanceOf(
            'Whoops\Handler\PrettyPageHandler',
            $this->debugger->getHandlers()[0]
        );
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

        $this->assertInstanceOf(
            'Whoops\Handler\CallbackHandler',
            $this->debugger->getHandlers()[0]
        );
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
