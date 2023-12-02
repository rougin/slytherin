<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use Rougin\Slytherin\Routing\Route;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Dispatching\Router
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
        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $this->router = new Router;
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
        $this->assertNull($this->router->parsed());
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

    /**
     * Tests $router->setPrefix() to add additional prefix in new routes.
     *
     * @return void
     */
    public function testSetPrefixMethod()
    {
        $this->router->setPrefix('/v1/slytherin');

        $route = $this->routes[0];

        $this->router->get($route->getUri(), $route->getHandler());

        $expected = '/v1/slytherin/';

        $route = $this->router->getRoute($route->getMethod(), $expected);

        $actual = $route->getUri();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests $router->restful() to add additional routes based on route.
     *
     * @return void
     */
    public function testRestfulMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->router->restful('new', $class);

        $this->assertCount(6, $this->router->getRoutes());
    }

    /**
     * Tests $router->addRoutes() to add a listing of routes.
     *
     * @return void
     */
    public function testAddRoutesMethod()
    {
        $this->router->addRoutes($this->routes);

        $this->assertEquals($this->routes, $this->router->getRoutes());
    }

    /**
     * Tests if the newly added route exists in the router.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $route = $this->routes[0];

        $uri = $route->getUri();

        $this->router->get($uri, $route->getHandler());

        $exists = $this->router->has($route->getMethod(), $uri);

        $this->assertTrue($exists);
    }
}
