<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

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

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->post('/', 'NewClass@store');

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

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
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

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $dispatcher = new Dispatcher($router);

        $controller = new NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
    }
}
