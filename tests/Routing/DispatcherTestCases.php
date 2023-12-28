<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
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
     * @return void
     */
    public function test_dispatching_a_route_as_a_callback()
    {
        $this->exists(get_class($this->dispatcher));

        $route = $this->dispatcher->dispatch('GET', '/hi');

        $expected = 'Hi and this is a callback';

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_callback_with_argument()
    {
        $this->exists(get_class($this->dispatcher));

        $route = $this->dispatcher->dispatch('GET', '/hi/Slytherin');

        $expected = 'Hi Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_class()
    {
        $this->exists(get_class($this->dispatcher));

        $controller = new NewClass;

        $route = $this->dispatcher->dispatch('GET', '/');

        $expected = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_as_a_class_from_put_http_method()
    {
        $this->exists(get_class($this->dispatcher));

        $controller = new NewClass;

        $route = $this->dispatcher->dispatch('POST', '/');

        $expected = $controller->store();

        $actual = $this->resolve($route);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_an_error()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->dispatch('GET', (string) '/test');
    }

    /**
     * @return void
     */
    public function test_dispatching_a_route_with_an_invalid_http_method()
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
            // @codeCoverageIgnoreStart
            if (! interface_exists('FastRoute\Dispatcher'))
            {
                $this->markTestSkipped('FastRoute is not installed.');
            }
            // @codeCoverageIgnoreEnd
        }

        if ($dispatcher === 'Rougin\Slytherin\Routing\PhrouteDispatcher')
        {
            // @codeCoverageIgnoreStart
            if (! class_exists('Phroute\Phroute\Dispatcher'))
            {
                $this->markTestSkipped('Phroute is not installed.');
            }
            // @codeCoverageIgnoreEnd
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
            /** @var class-string */
            $class = $handler[0];

            /** @var string */
            $method = $handler[1];

            /** @var callable */
            $handler = array(new $class, $method);
        }

        $params = $route->getParams();

        return call_user_func_array($handler, $params);
    }
}
