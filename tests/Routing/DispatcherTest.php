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
    public function test_dispatching_a_route_with_a_fastroute_router()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = new FastRouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expected = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_a_phroute_router()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        $router = new PhrouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expected = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }
}
