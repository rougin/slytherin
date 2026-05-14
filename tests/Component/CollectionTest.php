<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\IoC\Vanilla\Container;
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
    public function test_failed_if_debugger_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::DEBUGGER, new NewClass);

        $this->components->getDebugger();
    }

    /**
     * @return void
     */
    public function test_failed_if_dispatcher_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::DISPATCHER, new NewClass);

        $this->components->getDispatcher();
    }

    /**
     * @return void
     */
    public function test_failed_if_http_request_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::REQUEST, new NewClass);

        $this->components->getHttpRequest();
    }

    /**
     * @return void
     */
    public function test_failed_if_http_response_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::RESPONSE, new NewClass);

        $this->components->getHttpResponse();
    }

    /**
     * @return void
     */
    public function test_failed_if_middleware_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::MIDDLEWARE, new NewClass);

        $this->components->getMiddleware();
    }

    /**
     * @return void
     */
    public function test_failed_if_template_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->components->set(System::TEMPLATE, new NewClass);

        $this->components->getTemplate();
    }

    /**
     * @return void
     */
    public function test_passed_if_container_set()
    {
        // Set the container component ----------
        $expect = new Container;

        $this->components->setContainer($expect);
        // --------------------------------------

        // Verify the container was stored ---------
        $actual = $this->components->getContainer();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_debugger_set()
    {
        // Set the debugger component ----------
        $expect = new Debugger;

        $this->components->setDebugger($expect);
        // -------------------------------------

        // Verify the debugger was stored ---------
        $actual = $this->components->getDebugger();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_dependency_injector_set()
    {
        // Set the dependency injector component ---------
        $expect = new Container;

        $this->components->setDependencyInjector($expect);
        // -----------------------------------------------

        // Verify the injector was stored -------------------
        $actual = $this->components->getDependencyInjector();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_set()
    {
        // Set the dispatcher component ----------
        $expect = new Dispatcher(new Router);

        $this->components->setDispatcher($expect);
        // ---------------------------------------

        // Verify the dispatcher was stored ---------
        $actual = $this->components->getDispatcher();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_error_handler_set()
    {
        // Set the error handler component ---------
        $expect = new Debugger;

        $this->components->setErrorHandler($expect);
        // -----------------------------------------

        // Verify the handler was stored --------------
        $actual = $this->components->getErrorHandler();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_http_interface_set()
    {
        // Create a server request and response ---
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $response = new Response;

        $expect = array($request, $response);
        // ----------------------------------------

        // Set the HTTP interface components -----------
        $this->components->setHttp($request, $response);
        // ---------------------------------------------

        // Verify the HTTP components were stored ---
        $actual = $this->components->getHttp();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_http_request_set()
    {
        // Create a server request -----------
        $server = array('REQUEST_URI' => '/');
        $server['REQUEST_METHOD'] = 'GET';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $expect = new ServerRequest($server);
        // -----------------------------------

        // Set the HTTP request component ---------
        $this->components->setHttpRequest($expect);
        // ----------------------------------------

        // Verify the request was stored -------------
        $actual = $this->components->getHttpRequest();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_http_response_set()
    {
        // Set the HTTP response component ---------
        $expect = new Response;

        $this->components->setHttpResponse($expect);
        // -----------------------------------------

        // Verify the response was stored -------------
        $actual = $this->components->getHttpResponse();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_set()
    {
        $this->checkIfInteropExists();

        // Set the middleware component ----------
        $expect = new VanillaMiddleware;

        $this->components->setMiddleware($expect);
        // ---------------------------------------

        // Verify the middleware was stored ---------
        $actual = $this->components->getMiddleware();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_no_components_set()
    {
        $this->assertFalse($this->components->has(System::TEMPLATE));
    }

    /**
     * @return void
     */
    public function test_passed_if_template_set()
    {
        $this->checkIfTwigExists();

        $twig = new TwigLoader;

        // Load the Twig environment -------------
        $path = __DIR__ . '/../Fixture/Templates';

        $env = $twig->load($path);

        $expect = new TwigRenderer($env);
        // ---------------------------------------

        // Set the template component ----------
        $this->components->setTemplate($expect);
        // -------------------------------------

        // Verify the template was stored ---------
        $actual = $this->components->getTemplate();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->components = new Collection;
    }
}
