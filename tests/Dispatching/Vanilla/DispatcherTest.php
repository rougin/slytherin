<?php

namespace Rougin\Slytherin\Test\Dispatching\Vanilla;

use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Dispatching\Dispatcher;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;

/**
 * Dispatcher Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends PHPUnit_Framework_TestCase
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
        $routes = [
            [ 'GET', '/', [ 'Rougin\Slytherin\Test\Fixture\TestClass', 'index' ] ],
            [ 'POST', '/', [ 'Rougin\Slytherin\Test\Fixture\TestClass', 'store' ] ],
            [ 'GET', '/hi', function () { return 'Hi'; } ],
            [ 'TEST', '/hello', function () { return 'It must not go through here'; } ],
        ];

        $this->dispatcher = new Dispatcher(new Router($routes));
    }

    /**
     * Tests if dispatch() returned successfully with a class.
     * 
     * @return void
     */
    public function testDispatchMethodWithClass()
    {
        $controller = new TestClass;

        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/');

        $callback[0] = new $callback[0];

        $result = call_user_func_array($callback, $parameters);

        $this->assertEquals($controller->index(), $result);
    }

    /**
     * Tests if dispatch() returned successfully with a class.
     * 
     * @return void
     */
    public function testDispatchMethodWithClassAndPostMethod()
    {
        $controller = new TestClass;

        list($callback, $parameters) = $this->dispatcher->dispatch('POST', '/');

        $callback[0] = new $callback[0];

        $result = call_user_func_array($callback, $parameters);

        $this->assertEquals($controller->store(), $result);
    }

    /**
     * Tests if dispatch() returned successfully with a closure.
     * 
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/hi');

        $result = call_user_func($callback, $parameters);

        $this->assertEquals('Hi', $result);
    }

    /**
     * Tests if dispatch() returned successfully with an error.
     * 
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('UnexpectedValueException');

        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned successfully with an invalid method.
     * 
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('UnexpectedValueException');

        list($callback, $parameters) = $this->dispatcher->dispatch('TEST', '/hello');
    }

    /**
     * Tests if the dispatcher is implemented in DispatcherInterface.
     * 
     * @return void
     */
    public function testDispatcherInterface()
    {
        $interface = 'Rougin\Slytherin\Dispatching\DispatcherInterface';

        $this->assertInstanceOf($interface, $this->dispatcher);
    }
}
