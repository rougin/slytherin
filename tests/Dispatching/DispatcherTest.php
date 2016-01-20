<?php

namespace Rougin\Slytherin\Test\Dispatching;

use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Dispatching\Dispatcher;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixtures\TestController;

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
            [
                'GET',
                '/',
                [
                    'Rougin\Slytherin\Test\Fixtures\TestController',
                    'index'
                ]
            ],
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
        $controller = new TestController;

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
