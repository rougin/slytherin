<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

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
    protected function doSetUp()
    {
        $this->dispatcher = new Dispatcher($this->getRouter());
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

        // Dispatch using the generic dispatcher -----
        $dispatcher = new Dispatcher($router);
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
    public function test_passed_if_phroute_router_dispatched()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        // Set up a Phroute router -------------------
        $router = new PhrouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');
        // -------------------------------------------

        // Dispatch using the generic dispatcher -----
        $dispatcher = new Dispatcher($router);
        // -------------------------------------------

        // Verify the route is dispatched correctly ---
        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }
}
