<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends \PHPUnit_Framework_TestCase
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
        $routes = array(array('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

        $router = new Router($routes);

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->post('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $this->dispatcher = new Dispatcher($router);
    }

    /**
     * Tests DispatcherInterface::dispatch with class.
     *
     * @return void
     */
    public function testDispatchMethodWithClass()
    {
        $this->exists(get_class($this->dispatcher));

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $this->dispatcher->dispatch('GET', '/');

        $this->assertEquals($controller->index(), $this->result($function));
    }

    /**
     * Tests DispatcherInterface::dispatch with class and POST HTTP method.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPostHttpMethod()
    {
        $this->exists(get_class($this->dispatcher));

        $controller = new \Rougin\Slytherin\Fixture\Classes\NewClass;

        list($function) = $this->dispatcher->dispatch('POST', '/');

        $this->assertEquals($controller->store(), $this->result($function));
    }

    /**
     * Tests DispatcherInterface::dispatch with callback.
     *
     * @return void
     */
    public function testDispatchMethodWithCallback()
    {
        $this->exists(get_class($this->dispatcher));

        list($function) = $this->dispatcher->dispatch('GET', '/hi');

        $this->assertEquals('Hi and this is a callback', $this->result($function));
    }

    /**
     * Tests DispatcherInterface::dispatch with error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->exists(get_class($this->dispatcher));

        $this->setExpectedException('UnexpectedValueException', 'Route "/test" not found');

        $this->dispatcher->dispatch('GET', '/test');
    }

    /**
     * Tests DispatcherInterface::dispatch with invalid HTTP method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->exists(get_class($this->dispatcher));

        $this->setExpectedException('UnexpectedValueException', 'Used method is not allowed');

        $this->dispatcher->dispatch('TEST', '/');
    }

    /**
     * Returns result from the dispatched route.
     *
     * @param  array $function
     * @return mixed
     */
    protected function result($function)
    {
        if (is_array($function) === true) {
            list($callback, $parameters) = $function;

            if (is_array($callback)) {
                list($class, $method) = $callback;

                $callback = array(new $class, $method);
            }

            return call_user_func_array($callback, $parameters);
        }

        return $function;
    }

    /**
     * Verifies the specified dispatcher if it exists.
     *
     * @param  string $dispatcher
     * @return void
     */
    protected function exists($dispatcher)
    {
        switch ($dispatcher) {
            case 'Rougin\Slytherin\Routing\FastRouteDispatcher':
                if (! interface_exists('FastRoute\Dispatcher')) {
                    $this->markTestSkipped('FastRoute is not installed.');
                }

                break;
            case 'Rougin\Slytherin\Routing\PhrouteDispatcher':
                if (! class_exists('Phroute\Phroute\Dispatcher')) {
                    $this->markTestSkipped('Phroute is not installed.');
                }

                break;
        }
    }
}
