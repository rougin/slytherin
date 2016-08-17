<?php

namespace Rougin\Slytherin\Test\Component;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

use Rougin\Slytherin\IoC\Container;
use Rougin\Slytherin\Debug\Debugger;
use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Dispatching\Dispatcher;

use PHPUnit_Framework_TestCase;

/**
 * Component Collection Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up the component class.
     *
     * @return void
     */
    public function setUp()
    {
        $this->components = new Collection;
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

    /**
     * Tests if getComponent() returns null.
     * 
     * @return void
     */
    public function testGetNullComponent()
    {
        $this->assertNull($this->components->getContainer());
    }
}
