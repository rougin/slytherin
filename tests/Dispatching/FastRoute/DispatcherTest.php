<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use Rougin\Slytherin\Routing\Route;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! interface_exists('FastRoute\Dispatcher')) {
            $this->markTestSkipped('FastRoute is not installed.');
        }

        $routes = array(
            array(
                'GET',
                '/',
                array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'),
                'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware'
            ),
            array('GET', '/hi', function () {
                return 'Hi';
            }),
            array('TEST', '/hello', function () {
                return 'It must not go through here';
            }),
        );

        $router = new \Rougin\Slytherin\Dispatching\FastRoute\Router($routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\FastRoute\Dispatcher($router);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Tests if dispatch() returned successfully.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        $route = $this->dispatcher->dispatch('GET', '/');

        list($class, $method) = $route->getHandler();

        $object = array(new $class, $method);

        $result = call_user_func_array($object, $route->getParams());

        $this->assertEquals($controller->index(), $result);
    }

    /**
     * Tests if dispatch() returned successfully with a closure.
     *
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        $route = $this->dispatcher->dispatch('GET', '/hi');

        $callback = $route->getHandler();

        $parameters = $route->getParams();

        $result = call_user_func($callback, $parameters);

        $this->assertEquals('Hi', $result);
    }

    /**
     * Tests if dispatch() returned with an error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned with an invalid method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->dispatcher->dispatch('TEST', '/hi');
    }

    /**
     * Tests if dispatch() returned successfully with a middleware.
     *
     * @return void
     */
    public function testDispatchMethodWithMiddleware()
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
        $routes = array(array('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index', 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware'));

        $router = new \Rougin\Slytherin\Dispatching\Vanilla\Router($routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\FastRoute\Dispatcher($router);

        $expected = new Route('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index', 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $this->assertEquals($expected, $dispatcher->dispatch('GET', '/'));
    }
}
