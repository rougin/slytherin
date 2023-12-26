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
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\TwigLoader;
use Rougin\Slytherin\Template\TwigRenderer;
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
        $expected = new Container;

        $this->components->setContainer($expected);

        $actual = $this->components->getContainer();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setDependencyInjector() method.
     *
     * @return void
     */
    public function testSetDependencyInjectorMethod()
    {
        $expected = new Container;

        $this->components->setDependencyInjector($expected);

        $actual = $this->components->getDependencyInjector();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setDispatcher() method.
     *
     * @return void
     */
    public function testSetDispatcherMethod()
    {
        $expected = new Dispatcher(new Router);

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
     * Tests the setErrorHandler() method.
     *
     * @return void
     */
    public function testSetErrorHandlerMethod()
    {
        $expected = new Debugger;

        $this->components->setErrorHandler($expected);

        $actual = $this->components->getErrorHandler();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the setHttp() method.
     *
     * @return void
     */
    public function testSetHttpMethod()
    {
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
     * Tests the setMiddleware() method.
     *
     * @return void
     */
    public function testSetTemplateMethod()
    {
        $twig = new TwigLoader;

        if (! $twig->exists())
        {
            $this->markTestSkipped('Twig is not installed.');
        }

        /** @var string */
        $path = realpath(__DIR__ . '/../../Fixture/Templates');

        $environment = $twig->load($path);

        $expected = new TwigRenderer($environment);

        $this->components->setTemplate($expected);

        $actual = $this->components->getTemplate();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the has() method.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $this->assertFalse($this->components->has(System::TEMPLATE));
    }
}
