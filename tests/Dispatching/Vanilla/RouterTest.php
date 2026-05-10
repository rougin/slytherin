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
    public function test_passed_if_empty_route_retrieved()
    {
        // Verify a non-existent route returns null ---
        $this->assertNull($this->router->getRoute('GET', '/test'));
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_existing_route_checked()
    {
        // Add a route to the router ---------
        $route = $this->routes[0];

        $uri = $route->getUri();

        $this->router->get($uri, $route->getHandler());
        // -----------------------------------

        // Verify the route exists ---
        $exists = $this->router->has($route->getMethod(), $uri);

        $this->assertTrue($exists);
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_routes_retrieved()
    {
        // Verify parsed routes return null for vanilla router ---
        $this->assertNull($this->router->parsed());
        // -------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_routes_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Add RESTful routes for the resource ---
        $this->router->restful('new', $class);
        // ---------------------------------------

        // Verify six routes were created (index, store, show, update, delete, new) ---
        $expect = 6;

        $actual = $this->router->getRoutes();

        $this->assertCount($expect, $actual);
        // ----------------------------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added()
    {
        // Get details from the sample route ------------
        $expect = $this->routes[0];

        $method = $expect->getMethod();

        $uri = $expect->getUri();

        $handler = $expect->getHandler();
        // ----------------------------------------------

        // Add the route to the router -------------
        $this->router->addRoute($method, $uri, $handler);
        // -----------------------------------------

        // Verify the route is returned correctly ---
        $actual = $this->router->getRoute($method, $uri);

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_with_prefix()
    {
        // Set a URI prefix for the router ---
        $this->router->setPrefix('/v1/slytherin');
        // -----------------------------------

        // Add a route using the magic get method ---
        $route = $this->routes[0];

        $this->router->get($route->getUri(), $route->getHandler());
        // -----------------------------------------------

        // Verify the route includes the prefix ---------------
        $expect = '/v1/slytherin/';

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->getRoute($route->getMethod(), $expect);

        $actual = $route->getUri();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_router_interface_checked()
    {
        $interface = 'Rougin\Slytherin\Routing\RouterInterface';

        // Verify the router implements the expected interface ---
        $this->assertInstanceOf($interface, $this->router);
        // -------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_added()
    {
        // Add all sample routes at once ---
        $this->router->addRoutes($this->routes);
        // ---------------------------------

        // Verify the routes match the input ----
        $expect = $this->routes;

        $actual = $this->router->getRoutes();

        $this->assertEquals($expect, $actual);
        // --------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        $this->routes[] = $route;
        // --------------------------------------------------

        $this->router = new Router;
    }
}
