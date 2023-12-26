<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
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
     * @var \Rougin\Slytherin\Dispatching\FastRoute\Router
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
        // @codeCoverageIgnoreStart
        if (! class_exists('FastRoute\RouteCollector'))
        {
            $this->markTestSkipped('FastRoute is not installed.');
        }
        // @codeCoverageIgnoreEnd

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $collector = new RouteCollector(new Std, new GroupCountBased);

        $this->router = new Router;

        $this->router->setCollector($collector);
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

        $this->assertInstanceOf('Closure', $this->router->parsed());
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
