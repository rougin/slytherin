<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\Router
     */
    protected $router;

    /**
     * @var \Rougin\Slytherin\Routing\RouteInterface[]
     */
    protected $routes = array();

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $this->router = new Router;
    }

    /**
     * @return void
     */
    public function test_adding_a_route()
    {
        // Returns details from the sample route ---
        $expected = $this->routes[0];

        $method = $expected->getMethod();

        $uri = $expected->getUri();

        $handler = $expected->getHandler();
        // -----------------------------------------

        $this->router->addRoute($method, $uri, $handler);

        $actual = $this->router->getRoute($method, $uri);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_an_empty_route()
    {
        $this->assertNull($this->router->getRoute('GET', '/test'));
    }

    /**
     * @return void
     */
    public function test_getting_parsed_routes()
    {
        $this->assertNull($this->router->parsed());
    }

    /**
     * @return void
     */
    public function test_checking_the_router_interface()
    {
        $interface = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($interface, $this->router);
    }

    /**
     * @return void
     */
    public function test_adding_a_route_with_a_prefix()
    {
        $this->router->setPrefix('/v1/slytherin');

        $route = $this->routes[0];

        $this->router->get($route->getUri(), $route->getHandler());

        $expected = '/v1/slytherin/';

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->getRoute($route->getMethod(), $expected);

        $actual = $route->getUri();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_adding_a_route_as_a_restful()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->router->restful('new', $class);

        $expected = (int) 6;

        $actual = $this->router->getRoutes();

        $this->assertCount($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_adding_multiple_routes()
    {
        $this->router->addRoutes($this->routes);

        $expected = $this->routes;

        $actual = $this->router->getRoutes();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_an_existing_route()
    {
        $route = $this->routes[0];

        $uri = $route->getUri();

        $this->router->get($uri, $route->getHandler());

        $exists = $this->router->has($route->getMethod(), $uri);

        $this->assertTrue($exists);
    }
}
