<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use Rougin\Slytherin\Dispatching\DispatcherTestCases;
use Rougin\Slytherin\Dispatching\Vanilla\Router as Vanilla;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Routing\Route;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    public function test_failed_if_unmatched_http_method()
    {
        $expect = 'BadMethodCallException';

        $this->doExpectException($expect);

        // Verify against a non-matching HTTP method ---
        $this->self->dispatch('TEST', '/hi');
        // ---------------------------------------------
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

        $route = $this->self->dispatch('GET', '/');

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
    public function test_passed_if_vanilla_router_dispatched()
    {
        $middle = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $route = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        // Set up the vanilla Slytherin router ----
        $item = array('GET', '/', $route, $middle);

        $router = new Vanilla(array($item));
        // ----------------------------------------

        // Dispatch using the FastRoute dispatcher ---
        $dispatcher = new Dispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly -----
        $expect = new Route('GET', '/', $route, $middle);

        $actual = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfFastRouteExists();

        $middle = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $route = array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index');

        // Specify the routes to be checked -----------
        $routes = array();

        $routes[] = array('GET', '/', $route, $middle);

        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });

        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });
        // --------------------------------------------

        $router = new Router($routes);

        $this->self = new Dispatcher($router);
    }
}
