<?php

namespace Rougin\Slytherin\Dispatching;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\RouteInterface[]
     */
    protected $routes = array();

    /**
     * @var \Rougin\Slytherin\Routing\Router
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_empty_route_found()
    {
        $actual = $this->self->getRoute('GET', '/test');

        $this->assertNull($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added()
    {
        // Get details from the sample route ---
        $expect = $this->routes[0];

        $method = $expect->getMethod();

        $uri = $expect->getUri();

        $handler = $expect->getHandler();
        // -------------------------------------

        // Add the route to the router ----------------
        $this->self->addRoute($method, $uri, $handler);
        // --------------------------------------------

        // Verify the route is returned correctly -----
        $actual = $this->self->getRoute($method, $uri);

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_router_exists()
    {
        $expect = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($expect, $this->self);
    }
}
