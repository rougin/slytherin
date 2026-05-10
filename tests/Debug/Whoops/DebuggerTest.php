<?php

namespace Rougin\Slytherin\Debug\Whoops;

use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;
use Whoops\Handler\PrettyPageHandler;

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
     * @var \Rougin\Slytherin\Debug\Whoops\Debugger
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_callback_handler_set()
    {
        // Set a callback as a Whoops handler -----
        $fn = function ()
        {
            return 'Hello';
        };

        $this->self->setHandler($fn);

        $handlers = $this->self->getHandlers();
        // ----------------------------------------

        /** @var class-string<\Whoops\Handler\CallbackHandler> */
        $expect = 'Whoops\Handler\CallbackHandler';

        // Verify the handler is a CallbackHandler ---
        $actual = $handlers[0];

        $this->assertInstanceOf($expect, $actual);
        // -------------------------------------------
    }

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
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_error_reporting_displayed()
    {
        // Activate the Whoops debugger display ---
        $this->self->display();
        // ---------------------------------------

        // Verify error reporting is set to E_ALL ---
        $expect = E_ALL;

        $actual = error_reporting();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------

        // [NOTE] Adding these as this was being ---
        // marked as risky starting in PHP 8.2 -----
        restore_error_handler();

        restore_exception_handler();
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_pretty_page_handler_set()
    {
        // Set a PrettyPageHandler on the debugger --------
        $this->self->setHandler(new PrettyPageHandler);

        $handlers = $this->self->getHandlers();
        // ------------------------------------------------

        /** @var class-string<\Whoops\Handler\PrettyPageHandler> */
        $expect = 'Whoops\Handler\PrettyPageHandler';

        // Verify the handler is a PrettyPageHandler ---
        $actual = $handlers[0];

        $this->assertInstanceOf($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfWhoopsExists();

        $this->self = new Debugger(new \Whoops\Run);
    }
}
