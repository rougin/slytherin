<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
 * Dispatcher Test
 *
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
     * Sets up the dispatcher.
     *
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
     * Tests if dispatch() returned successfully with a class.
     *
     * @return void
     */
    public function testDispatchMethodWithClass()
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
     * Tests if dispatch() returned successfully with a class.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPostMethod()
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
     * Tests if dispatch() returned successfully with a closure.
     *
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        $route = $this->dispatcher->dispatch('GET', '/hi');

        /** @var callable */
        $callback = $route->getHandler();

        $params = $route->getParams();

        $actual = call_user_func($callback, $params);

        $this->assertEquals('Hi', $actual);
    }

    /**
     * Tests if dispatch() returned successfully with an error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned successfully with an invalid method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('TEST', '/hello');
    }

    /**
     * Tests if the dispatcher is implemented in DispatcherInterface.
     *
     * @return void
     */
    public function testDispatcherInterface()
    {
        $interface = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $this->assertInstanceOf($interface, $this->dispatcher);
    }
}
