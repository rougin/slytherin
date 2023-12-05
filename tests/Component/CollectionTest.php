<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\IoC\Vanilla\Container;
use Rougin\Slytherin\Middleware\Interop;
use Rougin\Slytherin\Middleware\VanillaMiddleware;
use Rougin\Slytherin\Testcase;

/**
 * Component Collection Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CollectionTest extends Testcase
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
        $this->components = new Collection;
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

        $expected = new Container;

        $this->components->setContainer($expected);

        $actual = $this->components->getContainer();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setDispatcher() method.
     *
     * @return void
     */
    public function testSetDispatcherMethod()
    {
        $router = new Router;

        $expected = new Dispatcher($router);

        $this->components->setDispatcher($expected);

        $actual = $this->components->getDispatcher();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setDebugger() method.
     *
     * @return void
     */
    public function testSetDebuggerMethod()
    {
        $expected = new Debugger;

        $this->components->setDebugger($expected);

        $actual = $this->components->getDebugger();

        $this->assertEquals($expected, $actual);
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

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $response = new Response;

        $expected = array($request, $response);

        $this->components->setHttp($request, $response);

        $actual = $this->components->getHttp();

        $this->assertEquals($expected, $actual);
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

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $expected = new ServerRequest($server);

        $this->components->setHttpRequest($expected);

        $actual = $this->components->getHttpRequest();

        $this->assertEquals($expected, $actual);
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

        $expected = new Response;

        $this->components->setHttpResponse($expected);

        $actual = $this->components->getHttpResponse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setMiddleware() method.
     *
     * @return void
     */
    public function testSetMiddlewareMethod()
    {
        if (! interface_exists('Psr\Http\Message\ResponseInterface'))
        {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not yet installed');
        }

        $expected = new VanillaMiddleware;

        $this->components->setMiddleware($expected);

        $actual = $this->components->getMiddleware();

        $this->assertEquals($expected, $actual);
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
