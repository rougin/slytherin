<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use Rougin\Slytherin\Dispatching\Vanilla\Router as Vanilla;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return void
     */
    public function test_failed_if_bad_method_exception_raised()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        $this->dispatcher->dispatch('TEST', '/hi');
    }

    /**
     * @return void
     */
    public function test_failed_if_http_method_invalid()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        $this->dispatcher->dispatch('TEST', '/hi');
    }

    /**
     * @return void
     */
    public function test_failed_if_route_not_found()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_interface_checked()
    {
        $expect = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $this->assertInstanceOf($expect, $this->dispatcher);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched()
    {
        // Dispatch the class-based route ---
        $class = new NewClass;

        $expect = $class->index();
        // ----------------------------------

        $route = $this->dispatcher->dispatch('GET', '/');

        /** @var string[] */
        $handler = $route->getHandler();

        $class = $handler[0];
        $method = $handler[1];

        $params = $route->getParams();

        // Verify if response is correct ----------------
        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_callback()
    {
        // Dispatch the callback-based route --------------
        $route = $this->dispatcher->dispatch('GET', '/hi');
        // ------------------------------------------------

        // Verify the callback result is correct ----
        /** @var callable */
        $callback = $route->getHandler();

        $params = $route->getParams();

        $actual = call_user_func($callback, $params);

        $this->assertEquals('Hi', $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_vanilla_router_dispatched()
    {
        $middleware = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $route = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        // Set up the vanilla Slytherin router --------
        $item = array('GET', '/', $route, $middleware);

        $router = new Vanilla(array($item));
        // --------------------------------------------

        // Dispatch using the FastRoute dispatcher ---
        $dispatcher = new Dispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly ---------
        $expect = new Route('GET', '/', $route, $middleware);

        $actual = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expect, $actual);
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfFastRouteExists();

        $middleware = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $route = array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index');

        // Specify the routes to be checked ---------------
        $routes = array();

        $routes[] = array('GET', '/', $route, $middleware);

        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });

        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });
        // ------------------------------------------------

        $router = new Router($routes);

        $this->dispatcher = new Dispatcher($router);
    }
}
