<?php

namespace Rougin\Slytherin\Component;

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
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CollectionTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Component\Collection
     */
    protected $components;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->components = new Collection;
    }

    /**
     * @return void
     */
    public function test_setting_the_container()
    {
        $expected = new Container;

        $this->components->setContainer($expected);

        $actual = $this->components->getContainer();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_dependency_injector()
    {
        $expected = new Container;

        $this->components->setDependencyInjector($expected);

        $actual = $this->components->getDependencyInjector();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_routing_dispatcher()
    {
        $expected = new Dispatcher(new Router);

        $this->components->setDispatcher($expected);

        $actual = $this->components->getDispatcher();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_debugger()
    {
        $expected = new Debugger;

        $this->components->setDebugger($expected);

        $actual = $this->components->getDebugger();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_error_handler()
    {
        $expected = new Debugger;

        $this->components->setErrorHandler($expected);

        $actual = $this->components->getErrorHandler();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_http_interface()
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
     * @return void
     */
    public function test_setting_the_http_request()
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
     * @return void
     */
    public function test_setting_the_http_response()
    {
        $expected = new Response;

        $this->components->setHttpResponse($expected);

        $actual = $this->components->getHttpResponse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_middleware()
    {
        // @codeCoverageIgnoreStart
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not yet installed');
        }
        // @codeCoverageIgnoreEnd

        $expected = new VanillaMiddleware;

        $this->components->setMiddleware($expected);

        $actual = $this->components->getMiddleware();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_template()
    {
        $twig = new TwigLoader;

        // @codeCoverageIgnoreStart
        if (! $twig->exists())
        {
            $this->markTestSkipped('Twig is not installed.');
        }
        // @codeCoverageIgnoreEnd

        /** @var string */
        $path = realpath(__DIR__ . '/../../Fixture/Templates');

        $environment = $twig->load($path);

        $expected = new TwigRenderer($environment);

        $this->components->setTemplate($expected);

        $actual = $this->components->getTemplate();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_existing_components()
    {
        $this->assertFalse($this->components->has(System::TEMPLATE));
    }
}
