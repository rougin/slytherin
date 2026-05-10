<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
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
     * @var \Rougin\Slytherin\Dispatching\FastRoute\Router
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
        $this->assertNull($this->router->getRoute('GET', '/test'));
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_routes_as_closure()
    {
        $this->router = new Router;

        // Verify parsed routes return a Closure ---
        $actual = $this->router->parsed();

        $this->assertInstanceOf('Closure', $actual);
        // -----------------------------------------
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

        // Add the route to the FastRoute router --------
        $this->router->addRoute($method, $uri, $handler);
        // ----------------------------------------------

        // Verify the route is returned correctly -------
        $actual = $this->router->getRoute($method, $uri);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_router_interface_checked()
    {
        $expect = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($expect, $this->router);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfFastRouteExists();

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        $this->routes[] = $route;
        // --------------------------------------------------

        $data = new GroupCountBased;

        $collector = new RouteCollector(new Std, $data);

        $this->router = new Router;

        $this->router->setCollector($collector);
    }
}
