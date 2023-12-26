<?php

namespace Rougin\Slytherin\Debug\Vanilla;

use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
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
     * @return void
     */
    protected function doSetUp()
    {
        $this->debugger = new Debugger;
    }

    /**
     * @return void
     */
    public function test_setting_the_environment()
    {
        $this->debugger->setEnvironment($this->environment);

        $expected = $this->environment;

        $actual = $this->debugger->getEnvironment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_error_reporting()
    {
        $this->debugger->display();

        $expected = E_ALL;

        $actual = error_reporting();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_debugger_instance()
    {
        $this->assertInstanceOf(System::DEBUGGER, $this->debugger);
    }
}
