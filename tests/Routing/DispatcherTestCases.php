<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_http_method_invalid()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        $this->exists(get_class($this->self));

        $this->self->dispatch('TEST', '/');
    }

    /**
     * @return void
     */
    public function test_failed_if_route_not_found()
    {
        $expect = 'BadMethodCallException';

        $this->doSetExpectedException($expect);

        $this->exists(get_class($this->self));

        $this->self->dispatch('GET', '/test');
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_callback()
    {
        $this->exists(get_class($this->self));

        // Dispatch the callback-based route --------
        $route = $this->self->dispatch('GET', '/hi');
        // ------------------------------------------

        // Verify the callback result is correct ---
        $expect = 'Hi and this is a callback';

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_class()
    {
        $this->exists(get_class($this->self));

        // Dispatch the class-based route ---------
        $controller = new NewClass;

        $route = $this->self->dispatch('GET', '/');
        // ----------------------------------------

        // Verify the controller response is correct ---
        $expect = $controller->index();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_post_class()
    {
        $this->exists(get_class($this->self));

        // Dispatch the POST-based route -----------
        $controller = new NewClass;

        $route = $this->self->dispatch('POST', '/');
        // -----------------------------------------

        // Verify the controller response is correct ---
        $expect = $controller->store();

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_with_argument()
    {
        $this->exists(get_class($this->self));

        // Dispatch the route with a URI argument ---
        $uri = '/hi/Slytherin';

        $route = $this->self->dispatch('GET', $uri);
        // ------------------------------------------

        // Verify the dynamic argument is resolved ---
        $expect = 'Hi Slytherin!';

        $actual = $this->resolve($route);

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * Verifies the specified dispatcher if it exists.
     *
     * @param string $dispatcher
     *
     * @return void
     */
    protected function exists($dispatcher)
    {
        if ($dispatcher === 'Rougin\Slytherin\Routing\FastRouteDispatcher')
        {
            $this->checkIfFastRouteExists();
        }

        if ($dispatcher === 'Rougin\Slytherin\Routing\PhrouteDispatcher')
        {
            $this->checkIfPhrouteExists();
        }
    }

    /**
     * Returns a list of sample routes.
     *
     * @param string|null $type
     *
     * @return \Rougin\Slytherin\Routing\RouterInterface
     */
    protected function getRouter($type = null)
    {
        $route = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        $routes = array(array('TEST', '/', $route));

        // Specify the route to be used -----------
        $router = new Router($routes);

        if ($type === 'fastroute')
        {
            $router = new FastRouteRouter($routes);
        }

        if ($type === 'phroute')
        {
            $router = new PhrouteRouter($routes);
        }
        // ----------------------------------------

        $class = 'Rougin\Slytherin\Fixture\Classes';

        $router->prefix('', $class);

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
     * @param \Rougin\Slytherin\Routing\RouteInterface $route
     *
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
