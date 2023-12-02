<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Phroute\Phroute\RouteCollector;
use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\Testcase;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * Sets up the router.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('Phroute\Phroute\RouteCollector'))
        {
            $this->markTestSkipped('Phroute is not installed.');
        }

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $this->router = new Router;

        $this->router->setCollector(new RouteCollector);
    }

    /**
     * Tests if the newly added route exists in the router.
     *
     * @return void
     */
    public function testAddRouteMethod()
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
     * Tests if the getRoute() method returns null.
     *
     * @return void
     */
    public function testEmptyGetRouteMethod()
    {
        $this->assertNull($this->router->getRoute('GET', '/test'));
    }

    /**
     * Tests if the existing routes are parsed.
     *
     * @return void
     */
    public function testParsedMethod()
    {
        $this->router = new Router;

        $this->assertInstanceOf('Phroute\Phroute\RouteDataArray', $this->router->parsed());
    }

    /**
     * Tests if the router is implemented in RouterInterface.
     *
     * @return void
     */
    public function testRouterInterface()
    {
        $interface = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($interface, $this->router);
    }
}
