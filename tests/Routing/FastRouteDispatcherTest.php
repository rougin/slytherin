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
    public function setUp()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteDispatcher');

        $router = new FastRouteRouter;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->post('/', 'NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $router->add('TEST', '/', 'NewClass@index');

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

        $router->get('/', 'NewClass@index');

        $dispatcher = new FastRouteDispatcher($router);

        $controller = new NewClass;

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
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

        list($function) = $dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
    }
}
