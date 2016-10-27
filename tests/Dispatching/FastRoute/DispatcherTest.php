<?php

namespace Rougin\Slytherin\Test\Dispatching\FastRoute;

use Rougin\Slytherin\Dispatching\FastRoute\Router;
use Rougin\Slytherin\Dispatching\FastRoute\Dispatcher;

use Rougin\Slytherin\Test\Fixture\Classes\NewClass;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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
        if (! interface_exists('FastRoute\Dispatcher')) {
            $this->markTestSkipped('FastRoute is not installed.');
        }

        $routes = [
            [
                'GET',
                '/',
                [ 'Rougin\Slytherin\Test\Fixture\Classes\NewClass', 'index' ],
                'Rougin\Slytherin\Test\Fixture\Middlewares\LastMiddleware'
            ],
            [ 'GET', '/hi', function () {
                return 'Hi';
            } ],
            [ 'TEST', '/hello', function () {
                return 'It must not go through here';
            } ],
        ];

        $this->dispatcher = new Dispatcher(new Router($routes));
    }

    /**
     * Tests if dispatch() returned successfully.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $controller = new NewClass;

        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/');

        if (is_object($callback)) {
            $result = call_user_func($callback, $parameters);
        } else {
            $callback[0] = new $callback[0];

            $result = call_user_func_array($callback, $parameters);
        }

        $this->assertEquals($controller->index(), $result);
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
     * Tests if dispatch() returned with an error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('UnexpectedValueException');

        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned with an invalid method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('UnexpectedValueException');

        list($callback, $parameters) = $this->dispatcher->dispatch('TEST', '/hi');
    }

    /**
     * Tests if dispatch() returned successfully with a middleware.
     *
     * @return void
     */
    public function testDispatchMethodWithMiddleware()
    {
        $this->setExpectedException('UnexpectedValueException');

        list($callback, $parameters) = $this->dispatcher->dispatch('TEST', '/hi');
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
