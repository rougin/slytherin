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
     * @var string
     */
    protected $environment = 'production';

    /**
     * @var \Rougin\Slytherin\Debug\Vanilla\Debugger
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_debugger_exists()
    {
        $expect = System::DEBUGGER;

        $this->assertInstanceOf($expect, $this->self);
    }

    /**
     * @return void
     */
    public function test_passed_if_environment_set()
    {
        // Set the environment on the debugger ---
        $expect = $this->environment;

        $this->self->setEnvironment($expect);
        // ---------------------------------------

        // Verify the environment was stored correctly ---
        $actual = $this->self->getEnvironment();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_error_reporting_displayed()
    {
        // Activate the debugger display ---
        $this->self->display();
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
        $this->self = new Debugger;
    }
}
