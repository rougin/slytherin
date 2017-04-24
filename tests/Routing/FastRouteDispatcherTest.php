<?php

namespace Rougin\Slytherin\Routing;

/**
 * FastRoute Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FastRouteDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $router = new FastRouteRouter;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->post('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');
        $router->get('/hi', function () { return 'Hi'; });

        $router->add('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $this->dispatcher = new FastRouteDispatcher($router);
    }

    /**
     * Tests FastRouteDispatcher::dispatch with Slytherin's Router.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndSlytherinRouter()
    {
        $router = new Router;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new FastRouteDispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }

    /**
     * Tests FastRouteDispatcher::dispatch with PhrouteRouter.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPhrouteRouter()
    {
        $router = new PhrouteRouter;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new FastRouteDispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }
}
