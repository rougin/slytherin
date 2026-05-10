<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Rougin\Slytherin\Dispatching\Vanilla\Router as Vanilla;
use Rougin\Slytherin\Fixture\Classes\NewClass;
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
     * @var array<integer, \Rougin\Slytherin\Routing\RouteInterface|mixed[]>
     */
    protected $routes = array();

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
        $controller = new NewClass;

        $expect = $controller->index();
        // ----------------------------------

        $route = $this->dispatcher->dispatch('GET', '/');

        /** @var string[] */
        $handler = $route->getHandler();

        $class = $handler[0];
        $method = $handler[1];

        $params = $route->getParams();

        // Verify the controller response is correct ----
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
        // Set up the vanilla Slytherin router ---
        $router = new Vanilla($this->routes);
        // ---------------------------------------

        // Dispatch using the Phroute dispatcher ---
        $dispatcher = new Dispatcher($router);
        // ----------------------------------------

        // Verify the route is dispatched correctly ------------------
        $controller = new NewClass;

        $expect = $controller->index();

        $route = $dispatcher->dispatch('GET', '/');

        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0];
        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        $this->checkIfPhrouteExists();
        // @codeCoverageIgnoreEnd

        $routes = array();
        $routes[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });
        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });

        $this->routes = $routes;

        $router = new Router($routes);

        $this->dispatcher = new Dispatcher($router);
    }
}
