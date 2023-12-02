<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * Phroute Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        $router = $this->getRouter('phroute');

        $this->dispatcher = new PhrouteDispatcher($router);
    }

    /**
     * Tests PhrouteDispatcher::dispatch with Slytherin's Router.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndSlytherinRouter()
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
     * Tests PhrouteDispatcher::dispatch with FastRouteRouter.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndFastRouteRouter()
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
