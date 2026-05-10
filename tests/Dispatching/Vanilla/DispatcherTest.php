<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return void
     */
    public function test_failed_if_http_method_invalid()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        // Attempt to dispatch with an invalid HTTP method ---
        $this->dispatcher->dispatch('TEST', '/hello');
        // ---------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_route_not_found()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        // Attempt to dispatch a non-existent route ---
        $this->dispatcher->dispatch('GET', '/test');
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_interface_checked()
    {
        $expect = 'Rougin\Slytherin\Routing\DispatcherInterface';

        // Verify the dispatcher implements the expected interface ---
        $this->assertInstanceOf($expect, $this->dispatcher);
        // -----------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_callback()
    {
        // Dispatch the callback-based route ---
        $route = $this->dispatcher->dispatch('GET', '/hi');
        // ------------------------------------------------

        // Verify the callback result is correct -----------------
        /** @var callable */
        $callback = $route->getHandler();

        $params = $route->getParams();

        $actual = call_user_func($callback, $params);

        $this->assertEquals('Hi', $actual);
        // -------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_class()
    {
        // Dispatch the class-based route -------------
        $newClass = new NewClass;

        $expect = $newClass->index();

        $route = $this->dispatcher->dispatch('GET', '/');
        // ---------------------------------------------

        // Verify the controller response is correct ------------------
        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0];
        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_post()
    {
        // Dispatch the POST-based route -------------
        $newClass = new NewClass;

        $expect = $newClass->store();

        $route = $this->dispatcher->dispatch('POST', '/');
        // -----------------------------------------------

        // Verify the controller response is correct ------------------
        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0];
        $method = $handler[1];

        $params = $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $routes = array();

        $routes[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $routes[] = array('POST', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'store'));
        $routes[] = array('GET', '/hi', function ()
        {
            return 'Hi';
        });
        $routes[] = array('TEST', '/hello', function ()
        {
            return 'It must not go through here';
        });

        $router = new Router($routes);

        $dispatcher = new Dispatcher($router);

        $this->dispatcher = $dispatcher;
    }
}
