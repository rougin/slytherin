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
    public function test_passed_if_phroute_router_dispatched()
    {
        $this->checkIfFastRouteExists();

        $this->checkIfPhrouteExists();

        // Set up a Phroute router -----------------
        $router = new PhrouteRouter;

        $class = 'Rougin\Slytherin\Fixture\Classes';

        $router->prefix('', $class);

        $router->get('/', 'NewClass@index');
        // -----------------------------------------

        $dispatcher = new FastRouteDispatcher($router);

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
        $this->checkIfFastRouteExists();

        // Set up the vanilla Slytherin router -----
        $router = new Router;

        $class = 'Rougin\Slytherin\Fixture\Classes';

        $router->prefix('', $class);

        $router->get('/hi/:name', function ($name)
        {
            return 'Hello ' . $name . '!';
        });
        // -----------------------------------------

        $dispatcher = new FastRouteDispatcher($router);

        // Verify the route is dispatched correctly -----------
        $route = $dispatcher->dispatch('GET', '/hi/Slytherin');

        $expect = 'Hello Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfFastRouteExists();

        $router = $this->getRouter('fastroute');

        $this->self = new FastRouteDispatcher($router);
    }
}
