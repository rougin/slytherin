<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteDispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        $router = $this->getRouter('phroute');

        $this->dispatcher = new PhrouteDispatcher($router);
    }

    /**
     * @return void
     */
    public function test_passed_if_fastroute_router_dispatched()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        // Set up a FastRoute router -----------------
        $router = new FastRouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');
        // -------------------------------------------

        // Dispatch using the Phroute dispatcher ---
        $dispatcher = new PhrouteDispatcher($router);
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
        // Set up the vanilla Slytherin router -------
        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');
        // -------------------------------------------

        // Dispatch using the Phroute dispatcher ---
        $dispatcher = new PhrouteDispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly ---
        $route = $dispatcher->dispatch('GET', '/');

        $controller = new NewClass;

        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }
}
