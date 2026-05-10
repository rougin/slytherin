<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteDispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = $this->getRouter('fastroute');

        $this->dispatcher = new FastRouteDispatcher($router);
    }

    /**
     * @return void
     */
    public function test_passed_if_phroute_router_dispatched()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        // Set up a Phroute router -------------------
        $router = new PhrouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');
        // -------------------------------------------

        // Dispatch using the FastRoute dispatcher ---
        $dispatcher = new FastRouteDispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly ---
        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_slytherin_router_dispatched()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        // Set up the vanilla Slytherin router -------
        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/hi/:name', function ($name)
        {
            return 'Hello ' . $name . '!';
        });
        // -------------------------------------------

        // Dispatch using the FastRoute dispatcher ---
        $dispatcher = new FastRouteDispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly -----------------
        $route = $dispatcher->dispatch('GET', '/hi/Slytherin');

        $expect = 'Hello Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------------
    }
}
