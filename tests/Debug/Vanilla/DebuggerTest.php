<?php

namespace Rougin\Slytherin\Debug\Vanilla;

use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @return void
     */
    public function test_passed_if_debugger_instance_checked()
    {
        $expect = System::DEBUGGER;

        $this->assertInstanceOf($expect, $this->debugger);
    }

    /**
     * @return void
     */
    public function test_passed_if_environment_set()
    {
        // Set the environment on the debugger ---
        $expect = $this->environment;

        $this->debugger->setEnvironment($expect);
        // ---------------------------------------

        // Verify the environment was stored correctly ---
        $actual = $this->debugger->getEnvironment();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_error_reporting_displayed()
    {
        // Activate the debugger display ---
        $this->debugger->display();
        // --------------------------------

        // Verify error reporting is set to E_ALL ---
        $expect = E_ALL;

        $actual = error_reporting();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->debugger = new Debugger;
    }
}
