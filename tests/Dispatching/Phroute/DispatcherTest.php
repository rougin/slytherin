<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Rougin\Slytherin\Dispatching\Vanilla\Router as Vanilla;
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
     * @var array<int, array<int, \Rougin\Slytherin\Middleware\MiddlewareInterface[]|callable|string|string[]>>
     */
    protected $routes = array();

    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('Phroute\Phroute\Dispatcher'))
        {
            $this->markTestSkipped('Phroute is not installed.');
        }

        $routes = array();
        $routes[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $routes[] = array('GET', '/hi', function () { return 'Hi'; });
        $routes[] = array('TEST', '/hello', function () { return 'It must not go through here'; });

        $this->routes = $routes;

        $router = new Router($routes);

        $this->dispatcher = new Dispatcher($router);
    }

    /**
     * Tests if dispatch() returned successfully.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $controller = new NewClass;

        $expected = $controller->index();

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

        $this->dispatcher->dispatch('TEST', '/hi');
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

    /**
     * Tests if dispatch() can return successfully with a different router.
     *
     * @return void
     */
    public function testDispatchMethodWithDifferentRouter()
    {
        $router = new Vanilla($this->routes);

        $dispatcher = new Dispatcher($router);

        $controller = new NewClass;

        $expected = $controller->index();

        $route = $dispatcher->dispatch('GET', '/');

        /** @var string */
        $handler = $route->getHandler();

        $class = $handler[0]; $method = $handler[1];

        $params = (array) $route->getParams();

        /** @var callable */
        $object = array(new $class, $method);

        $actual = call_user_func_array($object, $params);

        $this->assertEquals($expected, $actual);
    }
}
