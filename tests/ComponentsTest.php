<?php

namespace Rougin\Slytherin\Test;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

use Rougin\Slytherin\Components;
use Rougin\Slytherin\IoC\Container;
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
     * Tests the setContainer() method.
     *
     * @return void
     */
    public function testSetContainerMethod()
    {
        $container = new Container;

        $this->components->setContainer($container);

        $this->assertEquals($container, $this->components->getContainer());
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

    /**
     * Tests the setHttp() method.
     *
     * @return void
     */
    public function testSetHttpMethod()
    {
        $response = new Response;
        $request = ServerRequestFactory::fromGlobals();

        $this->components->setHttp($request, $response);

        $this->assertEquals(
            [ $request, $response ],
            $this->components->getHttp()
        );
    }
}
