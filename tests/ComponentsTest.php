<?php

namespace Rougin\Slytherin\Test;

use Rougin\Slytherin\Components;
use Rougin\Slytherin\Debug\Debugger;
use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Dispatching\Dispatcher;

use PHPUnit_Framework_TestCase;

/**
 * Components Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ComponentsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up the component class.
     *
     * @return void
     */
    public function setUp()
    {
        $this->components = new Components;
    }

    /**
     * Tests the setDispatcher() method.
     *
     * @return void
     */
    public function testSetDispatcherMethod()
    {
        $dispatcher = new Dispatcher(new Router);

        $this->components->setDispatcher($dispatcher);

        $this->assertEquals($dispatcher, $this->components->getDispatcher());
    }

    /**
     * Tests the setDebugger() method.
     *
     * @return void
     */
    public function testSetDebuggerMethod()
    {
        $debugger = new Debugger;

        $this->components->setDebugger($debugger);

        $this->assertEquals($debugger, $this->components->getDebugger());
    }
}
