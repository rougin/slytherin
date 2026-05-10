<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Dispatching\DispatcherTestCases;
use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_class()
    {
        $route = $this->self->dispatch('GET', '/');

        // Dispatch the class-based route ---
        $newClass = new NewClass;

        $expect = $newClass->index();
        // ----------------------------------

        // Verify the controller response is correct ----
        /** @var string[] */
        $handler = $route->getHandler();

        $class = $handler[0];

        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_post()
    {
        $route = $this->self->dispatch('POST', '/');

        // Dispatch the POST-based route ---
        $newClass = new NewClass;

        $expect = $newClass->store();
        // ---------------------------------

        // Verify the controller response is correct ----
        /** @var string[] */
        $handler = $route->getHandler();

        $class = $handler[0];

        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $index = array($class, 'index');

        $store = array($class, 'store');

        // Prepare the routes to be used for testing ---
        $routes = array(array('GET', '/', $index));

        $routes[] = array('POST', '/', $store);

        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });

        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });
        // ---------------------------------------------

        $router = new Router($routes);

        $dispatcher = new Dispatcher($router);

        $this->self = $dispatcher;
    }
}
