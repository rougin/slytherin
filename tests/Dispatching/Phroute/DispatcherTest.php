<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Rougin\Slytherin\Dispatching\DispatcherTestCases;
use Rougin\Slytherin\Dispatching\Vanilla\Router as Vanilla;
use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @deprecated since ~0.9, use "Routing\PhrouteDispatcherTest" instead.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * @var array<integer, \Rougin\Slytherin\Routing\RouteInterface|mixed[]>
     */
    protected $routes = array();

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched()
    {
        // Dispatch the class-based route ---
        $controller = new NewClass;

        $expect = $controller->index();
        // ----------------------------------

        $route = $this->self->dispatch('GET', '/');

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
    public function test_passed_if_vanilla_router_dispatched()
    {
        // Set up the vanilla Slytherin router ---
        $router = new Vanilla($this->routes);
        // ---------------------------------------

        // Dispatch using the Phroute dispatcher ---
        $dispatcher = new Dispatcher($router);
        // -----------------------------------------

        // Verify the route is dispatched correctly -----
        $controller = new NewClass;

        $expect = $controller->index();

        $route = $dispatcher->dispatch('GET', '/');

        /** @var string[] */
        $handler = $route->getHandler();

        $class = $handler[0];

        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfPhrouteExists();

        $route = array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index');

        // Prepare the available routes for testing ---
        $routes = array(array('GET', '/', $route));

        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });

        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });
        // --------------------------------------------

        $this->routes = $routes;

        $router = new Router($routes);

        $this->self = new Dispatcher($router);
    }
}
