<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Rougin\Slytherin\Dispatching\Phroute\Router;
use Rougin\Slytherin\Dispatching\Phroute\Dispatcher;

use Rougin\Slytherin\Fixture\Classes\NewClass;

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
        if (! class_exists('Phroute\Phroute\Dispatcher')) {
            $this->markTestSkipped('Phroute is not installed.');
        }

        $routes = [
            [ 'GET', '/', [ 'Rougin\Slytherin\Fixture\Classes\NewClass', 'index' ] ],
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

        $expected = [ $controller->index(), null, [] ];

        $this->assertEquals($expected, $this->dispatcher->dispatch('GET', '/'));
    }

    /**
     * Tests if dispatch() returned successfully with a closure.
     *
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        $expected = [ 'Hi', null, [] ];

        $this->assertEquals($expected, $this->dispatcher->dispatch('GET', '/hi'));
    }

    /**
     * Tests if dispatch() returned successfully with an error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('Phroute\Phroute\Exception\HttpRouteNotFoundException');

        list($callback, $parameters) = $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests if dispatch() returned successfully with an invalid method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('Phroute\Phroute\Exception\HttpMethodNotAllowedException');

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
