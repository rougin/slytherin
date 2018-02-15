<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
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

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->post('/', 'NewClass@store');

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

        $controller = new NewClass;

        list($function) = $this->dispatcher->dispatch('GET', '/');

        $expected = (string) $controller->index();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with class and POST HTTP method.
     *
     * @return void
     */
    public function testDispatchMethodWithClassAndPostHttpMethod()
    {
        $this->exists(get_class($this->dispatcher));

        $controller = new NewClass;

        list($function) = $this->dispatcher->dispatch('POST', '/');

        $expected = (string) $controller->store();

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
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

        $expected = 'Hi and this is a callback';

        $result = $this->result($function);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('UnexpectedValueException');

        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->dispatch('GET', (string) '/test');
    }

    /**
     * Tests DispatcherInterface::dispatch with invalid HTTP method.
     *
     * @return void
     */
    public function testDispatchMethodWithInvalidMethod()
    {
        $this->setExpectedException('UnexpectedValueException');

        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->dispatch('TEST', (string) '/');
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

            if (is_array($callback) === true) {
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
        if ($dispatcher === 'Rougin\Slytherin\Routing\FastRouteDispatcher') {
            $exists = interface_exists('FastRoute\Dispatcher');

            $message = (string) 'FastRoute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }

        if ($dispatcher === 'Rougin\Slytherin\Routing\PhrouteDispatcher') {
            $exists = class_exists('Phroute\Phroute\Dispatcher');

            $message = (string) 'Phroute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }
    }
}
