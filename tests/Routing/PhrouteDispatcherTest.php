<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
    public function test_dispatching_a_route_with_a_slytherin_router()
    {
        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new PhrouteDispatcher($router);

        $route = $dispatcher->dispatch('GET', '/');

        $controller = new NewClass;

        $expected = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
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

        $dispatcher = new PhrouteDispatcher($router);

        $controller = new NewClass;

        $route = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }
}
