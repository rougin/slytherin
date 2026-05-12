<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Dispatching\RouterTestCases;
use Rougin\Slytherin\Routing\Route;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    public function test_passed_if_parsed_routes_found()
    {
        $this->assertNull($this->self->parsed());
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_routes_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Add RESTful routes for the resource ---
        $this->self->restful('new', $class);
        // ---------------------------------------

        // Verify six routes were created ---
        $expect = 6;

        $actual = $this->self->getRoutes();

        $this->assertCount($expect, $actual);
        // ----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_with_prefix()
    {
        // Set a URI prefix for the router -----
        $this->self->setPrefix('/v1/slytherin');
        // -------------------------------------

        // Add a route using the magic get method ---
        $route = $this->routes[0];

        $handler = $route->getHandler();

        $method = $route->getMethod();

        $this->self->get($route->getUri(), $handler);
        // ------------------------------------------

        // Verify the route includes the prefix ------------
        $expect = '/v1/slytherin/';

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->self->getRoute($method, $expect);

        $actual = $route->getUri();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_found()
    {
        // Add a route to the router ----
        $route = $this->routes[0];

        $handler = $route->getHandler();

        $uri = $route->getUri();

        $method = $route->getMethod();

        $this->self->get($uri, $handler);
        // ------------------------------

        // Verify the route exists ---------------
        $exists = $this->self->has($method, $uri);

        $this->assertTrue($exists);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_added()
    {
        // Add all sample routes at once -------
        $this->self->addRoutes($this->routes);
        // -------------------------------------

        // Verify the routes match the input ---
        $expect = $this->routes;

        $actual = $this->self->getRoutes();

        $this->assertEquals($expect, $actual);
        // -------------------------------------
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

        $this->self = new Router;
    }
}
