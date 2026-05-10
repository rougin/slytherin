<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Phroute\Phroute\RouteCollector;
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
     * @var \Rougin\Slytherin\Routing\PhrouteRouter
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
    public function test_passed_if_parsed_routes_retrieved()
    {
        // Create a fresh router without a collector ---
        $this->router = new Router;
        // ---------------------------------------------

        // Verify parsed routes return the Phroute data array ---
        /** @var class-string<\Phroute\Phroute\RouteDataArray> */
        $expect = 'Phroute\Phroute\RouteDataArray';

        $actual = $this->router->parsed();

        $this->assertInstanceOf($expect, $actual);
        // -------------------------------------------------------
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

        // Add the route to the Phroute router -----
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
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        $this->checkIfPhrouteExists();
        // @codeCoverageIgnoreEnd

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        $this->routes[] = $route;
        // --------------------------------------------------

        $this->router = new Router;

        $this->router->setCollector(new RouteCollector);
    }
}
