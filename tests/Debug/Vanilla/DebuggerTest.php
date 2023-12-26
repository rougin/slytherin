<?php

namespace Rougin\Slytherin\Debug\Vanilla;

use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Debug\Vanilla\Debugger
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

        $expected = $this->environment;

        $actual = $this->debugger->getEnvironment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->debugger->display();

        $expected = E_ALL;

        $actual = error_reporting();

        $this->assertEquals($expected, $actual);
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
