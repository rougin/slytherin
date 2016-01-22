<?php

namespace Rougin\Slytherin\Test\Dispatching\FastRoute;

use Rougin\Slytherin\Dispatching\FastRoute\Router;
use Rougin\Slytherin\Dispatching\DispatcherInterface;
use Rougin\Slytherin\Dispatching\FastRoute\Dispatcher;

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
            ['GET', '/', [TestController::class, 'index']],
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
        $this->assertInstanceOf(DispatcherInterface::class, $this->dispatcher);
    }
}
