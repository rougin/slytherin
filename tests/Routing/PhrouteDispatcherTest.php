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
        $this->checkIfPhrouteExists();

        $router = $this->getRouter('phroute');

        $this->self = new PhrouteDispatcher($router);
    }

    /**
     * @return void
     */
    public function test_passed_if_fastroute_router_dispatched()
    {
        $this->checkIfFastRouteExists();

        // Set up a FastRoute router ---------------
        $router = new FastRouteRouter;

        $class = 'Rougin\Slytherin\Fixture\Classes';

        $router->prefix('', $class);

        $router->get('/', 'NewClass@index');
        // -----------------------------------------

        // Dispatch using the Phroute dispatcher ----
        $dispatcher = new PhrouteDispatcher($router);
        // ------------------------------------------

        // Verify the route is dispatched correctly ---
        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_slytherin_router_dispatched()
    {
        // Set up the vanilla Slytherin router -----
        $router = new Router;

        $class = 'Rougin\Slytherin\Fixture\Classes';

        $router->prefix('', $class);

        $router->get('/', 'NewClass@index');
        // -----------------------------------------

        $dispatcher = new PhrouteDispatcher($router);

        // Verify the route is dispatched correctly ---
        $route = $dispatcher->dispatch('GET', '/');

        $controller = new NewClass;

        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }
}
