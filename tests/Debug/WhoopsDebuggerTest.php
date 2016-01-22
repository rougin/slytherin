<?php

namespace Rougin\Slytherin\Test\Debug;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Rougin\Slytherin\Debug\WhoopsDebugger;
use Rougin\Slytherin\Debug\DebuggerInterface;

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
        $handler = new PrettyPageHandler;

        $this->debugger->setHandler($handler);

        $this->assertContains($handler, $this->debugger->getHandlers());
    }

    /**
     * Tests if the debugger is implemented in DebuggerInterface.
     * 
     * @return void
     */
    public function testDebuggerInterface()
    {
        $this->assertInstanceOf(DebuggerInterface::class, $this->debugger);
    }
}
