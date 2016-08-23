<?php

namespace Rougin\Slytherin\Test\Debug\Vanilla;

use Rougin\Slytherin\Debug\Debugger;

use PHPUnit_Framework_TestCase;

/**
 * Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends PHPUnit_Framework_TestCase
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
        $this->debugger = new Debugger;
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
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->assertEquals('', $this->debugger->display());
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
