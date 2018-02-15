<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

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

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->post('/', 'NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $router->add('TEST', '/', 'NewClass@index');

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

        $controller = new NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
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

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
    }
}
