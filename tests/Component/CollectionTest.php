<?php

namespace Rougin\Slytherin\Component;

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
        $this->components = new \Rougin\Slytherin\Component\Collection;
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

        $container = new \Rougin\Slytherin\IoC\Vanilla\Container;

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
        $router = new \Rougin\Slytherin\Dispatching\Vanilla\Router;

        $dispatcher = new \Rougin\Slytherin\Dispatching\Vanilla\Dispatcher($router);

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
        $debugger = new \Rougin\Slytherin\Debug\Vanilla\Debugger;

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

        $server = array(
            'SERVER_NAME'    => 'localhost',
            'SERVER_PORT'    => '8000',
            'REQUEST_URI'    => '/',
            'REQUEST_METHOD' => 'GET',
        );

        $response = new \Rougin\Slytherin\Http\Response;
        $request  = new \Rougin\Slytherin\Http\ServerRequest($server);

        $this->components->setHttp($request, $response);

        $this->assertEquals(array($request, $response), $this->components->getHttp());
    }

    /**
     * Tests the setHttpRequest() method.
     *
     * @return void
     */
    public function testSetHttpRequestMethod()
    {
        if (! interface_exists('Psr\Http\Message\ServerRequestInterface')) {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        $server = array(
            'SERVER_NAME'    => 'localhost',
            'SERVER_PORT'    => '8000',
            'REQUEST_URI'    => '/',
            'REQUEST_METHOD' => 'GET',
        );

        $request  = new \Rougin\Slytherin\Http\ServerRequest($server);

        $this->components->setHttpRequest($request);

        $this->assertEquals($request, $this->components->getHttpRequest());
    }

    /**
     * Tests the setHttpResponse() method.
     *
     * @return void
     */
    public function testSetHttpResponseMethod()
    {
        if (! interface_exists('Psr\Http\Message\ResponseInterface')) {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        $response = new \Rougin\Slytherin\Http\Response;

        $this->components->setHttpResponse($response);

        $this->assertEquals($response, $this->components->getHttpResponse());
    }

    /**
     * Tests if get() returns null.
     *
     * @return void
     */
    public function testGetNullComponent()
    {
        $this->assertNull($this->components->getContainer());
    }
}
