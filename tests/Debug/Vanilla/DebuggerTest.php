<?php

namespace Rougin\Slytherin\Debug\Vanilla;

/**
 * Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends \Rougin\Slytherin\Testcase
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
        $this->debugger = new \Rougin\Slytherin\Debug\Vanilla\Debugger;
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
