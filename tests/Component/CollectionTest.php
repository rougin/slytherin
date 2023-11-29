<?php

namespace Rougin\Slytherin\Component;

/**
 * Component Collection Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CollectionTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Component\Collection
     */
    protected $components;

    /**
     * Sets up the component class.
     *
     * @return void
     */
    protected function doSetUp()
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
        if (! interface_exists('Psr\Container\ContainerInterface'))
        {
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
        if (! interface_exists('Psr\Http\Message\ResponseInterface'))
        {
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
        if (! interface_exists('Psr\Http\Message\ServerRequestInterface'))
        {
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
        if (! interface_exists('Psr\Http\Message\ResponseInterface'))
        {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        $response = new \Rougin\Slytherin\Http\Response;

        $this->components->setHttpResponse($response);

        $this->assertEquals($response, $this->components->getHttpResponse());
    }

    /**
     * Tests the setMiddleware() method.
     *
     * @return void
     */
    public function testSetMiddlewareMethod()
    {
        $response = 'Psr\Http\Message\ResponseInterface';

        interface_exists($response) || $this->markTestSkipped('PSR HTTP Message is not installed.');

        $middleware = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

        interface_exists($middleware) || $this->markTestSkipped('Interop Middleware is not installed.');

        $middleware = new \Rougin\Slytherin\Middleware\VanillaMiddleware;

        $this->components->setMiddleware($middleware);

        $this->assertEquals($middleware, $this->components->getMiddleware());
    }

    /**
     * Tests if get() returns null.
     *
     * @return void
     */
    public function testGetNullComponent()
    {
        $this->assertNull($this->components->getDebugger());
    }
}
