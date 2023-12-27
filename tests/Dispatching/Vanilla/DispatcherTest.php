<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
    protected function doSetUp()
    {
        $routes = array();

        $routes[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $routes[] = array('POST', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'store'));
        $routes[] = array('GET', '/hi', function () { return 'Hi'; });
        $routes[] = array('TEST', '/hello', function () { return 'It must not go through here'; });

        $router = new Router($routes);

        $dispatcher = new Dispatcher($router);

        $this->dispatcher = $dispatcher;
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_class()
    {
        $newClass = new NewClass;

        $expected = $newClass->index();

        $route = $this->dispatcher->dispatch('GET', '/');

        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0]; $method = $handler[1];

        $params = (array) $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_class_from_post_http_method()
    {
        $newClass = new NewClass;

        $expected = $newClass->store();

        $route = $this->dispatcher->dispatch('POST', '/');

        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0]; $method = $handler[1];

        $params = (array) $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_callback()
    {
        $route = $this->dispatcher->dispatch('GET', '/hi');

        /** @var callable */
        $callback = $route->getHandler();

        $params = $route->getParams();

        $actual = call_user_func($callback, $params);

        $this->assertEquals('Hi', $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_an_error()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_an_invalid_http_method()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('TEST', '/hello');
    }

    /**
     * @return void
     */
    public function test_checking_route_dispatcher_interace()
    {
        $interface = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $this->assertInstanceOf($interface, $this->dispatcher);
    }
}
