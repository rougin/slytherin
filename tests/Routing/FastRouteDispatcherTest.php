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
    public function test_dispatching_a_route_with_a_slytherin_router()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/hi/:name', function ($name)
        {
            return 'Hello ' . $name . '!';
        });

        $dispatcher = new FastRouteDispatcher($router);

        $route = $dispatcher->dispatch('GET', '/hi/Slytherin');

        $expected = (string) 'Hello Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_a_phroute_router()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $this->exists('Rougin\Slytherin\Routing\PhrouteRouter');

        $router = new PhrouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new FastRouteDispatcher($router);

        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }
}
