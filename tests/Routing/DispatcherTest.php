<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $routes = array(array('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

        $router = new Router($routes);

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->post('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $this->dispatcher = new Dispatcher($router);
    }

    /**
     * Tests Dispatcher::dispatch with FastRouteRouter.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndFastRouteRouter()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = new FastRouteRouter;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }

    /**
     * Tests Dispatcher::dispatch with PhrouteRouter.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPhrouteRouter()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteDispatcher');

        $router = new PhrouteRouter;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }
}
