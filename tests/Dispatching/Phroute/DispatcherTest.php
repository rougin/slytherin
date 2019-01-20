<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

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
     * @var array
     */
    protected $routes = array();

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

        $this->routes = array(
            array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index')),
            array('GET', '/hi', function () {
                return 'Hi';
            }),
            array('TEST', '/hello', function () {
                return 'It must not go through here';
            }),
        );

        $router = new \Rougin\Slytherin\Dispatching\Phroute\Router($this->routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\Phroute\Dispatcher($router);

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

        $expected = array($controller->index(), array());

        $this->assertEquals($expected, $this->dispatcher->dispatch('GET', '/'));
    }

    /**
     * Tests if dispatch() returned successfully with a closure.
     *
     * @return void
     */
    public function testDispatchMethodWithClosure()
    {
        $expected = array('Hi', array());

        $this->assertEquals($expected, $this->dispatcher->dispatch('GET', '/hi'));
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

        list($callback, $parameters) = $this->dispatcher->dispatch('TEST', '/hi');
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
        $router = new \Rougin\Slytherin\Dispatching\Vanilla\Router($this->routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\Phroute\Dispatcher($router);

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        $expected = array($controller->index(), array());

        $this->assertEquals($expected, $dispatcher->dispatch('GET', '/'));
    }
}
