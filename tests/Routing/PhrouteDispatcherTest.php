<?php

namespace Rougin\Slytherin\Routing;

/**
 * Phroute Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class PhrouteDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        $router = new PhrouteRouter;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->post('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $router->add('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

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

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new PhrouteDispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
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

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new PhrouteDispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }
}
