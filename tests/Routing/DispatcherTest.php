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
