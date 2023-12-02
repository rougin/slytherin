<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Tests DispatcherInterface::dispatch with callback.
     *
     * @return void
     */
    public function testDispatchMethodWithCallback()
    {
        $this->exists(get_class($this->dispatcher));

        $route = $this->dispatcher->dispatch('GET', '/hi');

        $expected = 'Hi and this is a callback';

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests DispatcherInterface::dispatch with callback and argument.
     *
     * @return void
     */
    public function testDispatchMethodWithCallbackAndArgument()
    {
        $this->exists(get_class($this->dispatcher));

        $route = $this->dispatcher->dispatch('GET', '/hi/Slytherin');

        $expected = 'Hi Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
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

        $route = $this->dispatcher->dispatch('GET', '/');

        $expected = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
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

        $route = $this->dispatcher->dispatch('POST', '/');

        $expected = $controller->store();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests DispatcherInterface::dispatch with error.
     *
     * @return void
     */
    public function testDispatchMethodWithError()
    {
        $this->setExpectedException('BadMethodCallException');

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
        $this->setExpectedException('BadMethodCallException');

        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->dispatch('TEST', (string) '/');
    }

    /**
     * Verifies the specified dispatcher if it exists.
     *
     * @param  string $dispatcher
     * @return void
     */
    protected function exists($dispatcher)
    {
        if ($dispatcher === 'Rougin\Slytherin\Routing\FastRouteDispatcher')
        {
            $exists = interface_exists('FastRoute\Dispatcher');

            $message = (string) 'FastRoute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }

        if ($dispatcher === 'Rougin\Slytherin\Routing\PhrouteDispatcher')
        {
            $exists = class_exists('Phroute\Phroute\Dispatcher');

            $message = (string) 'Phroute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }
    }

    /**
     * Returns a list of sample routes.
     *
     * @param  string|null $type
     * @return \Rougin\Slytherin\Routing\RouterInterface
     */
    protected function getRouter($type = null)
    {
        $routes = array(array('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

        $router = new Router($routes);

        if ($type === 'fastroute')
        {
            $router = new FastRouteRouter($routes);
        }

        if ($type === 'phroute')
        {
            $router = new PhrouteRouter($routes);
        }

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->post('/', 'NewClass@store');

        $router->get('/hi/{name}', function ($name)
        {
            return 'Hi ' . $name . '!';
        });

        $router->get('/hi', function ()
        {
            return 'Hi and this is a callback';
        });

        return $router;
    }

    /**
     * Returns result from the dispatched route.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface $route
     * @return mixed
     */
    protected function resolve(RouteInterface $route)
    {
        $handler = $route->getHandler();

        if (is_array($handler))
        {
            list($class, $method) = $handler;

            $handler = array(new $class, $method);
        }

        $params = $route->getParams();

        return call_user_func_array($handler, $params);
    }
}
