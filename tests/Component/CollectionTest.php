<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\IoC\Vanilla\Container;
use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;

/**
 * Component Collection Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
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
        if (! interface_exists('Interop\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

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
        if (! interface_exists('Psr\Http\Message\ResponseInterface')) {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        $response = new Response;
        $request = new ServerRequest;

        $this->components->setHttp($request, $response);

        $this->assertEquals([ $request, $response ], $this->components->getHttp());
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
