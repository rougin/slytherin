<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * FastRoute Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = $this->getRouter('fastroute');

        $this->dispatcher = new FastRouteDispatcher($router);
    }

    /**
     * Tests FastRouteDispatcher::dispatch with Slytherin's Router.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndSlytherinRouter()
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
     * Tests FastRouteDispatcher::dispatch with PhrouteRouter.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPhrouteRouter()
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
