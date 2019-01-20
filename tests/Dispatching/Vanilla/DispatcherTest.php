<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $routes = array(
            array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index')),
            array('POST', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'store')),
            array('GET', '/hi', function () {
                return 'Hi';
            }),
            array('TEST', '/hello', function () {
                return 'It must not go through here';
            }),
        );

        $router = new \Rougin\Slytherin\Dispatching\Vanilla\Router($routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\Vanilla\Dispatcher($router);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Tests if dispatch() returned successfully with a class.
     *
     * @return void
     */
    public function testDispatchMethodWithClass()
    {
        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $this->dispatcher->dispatch('GET', '/');

        list($callback, $parameters) = $function;

        list($class, $method) = $callback;

        $result = call_user_func_array(array(new $class, $method), $parameters);

        $this->assertEquals($controller->index(), $result);
    }

    /**
     * Tests if dispatch() returned successfully with a class.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPostMethod()
    {
        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $this->dispatcher->dispatch('POST', '/');

        list($callback, $parameters) = $function;

        list($class, $method) = $callback;

        $result = call_user_func_array(array(new $class, $method), $parameters);

        $this->assertEquals($controller->store(), $result);
    }

    /**
     * Tests if dispatch() returned successfully with a closure.
     *
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        list($function) = $this->dispatcher->dispatch('GET', '/hi');

        list($callback, $parameters) = $function;

        $this->assertEquals('Hi', call_user_func($callback, $parameters));
    }

    /**
     * Tests if dispatch() returned successfully with an error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('UnexpectedValueException');

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned successfully with an invalid method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('UnexpectedValueException');

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
