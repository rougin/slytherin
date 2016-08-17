<?php

namespace Rougin\Slytherin\Test\Dispatching\Vanilla;

use Rougin\Slytherin\Dispatching\Router;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;

/**
 * Router Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Dispatching\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $routes = [
        [ 'GET', '/', [ 'Rougin\Slytherin\Test\Fixture\TestClass', 'index' ], [] ],
    ];

    /**
     * Sets up the router.
     *
     * @return void
     */
    public function setUp()
    {
        $this->router = new Router;
    }

    /**
     * Tests if the newly added route exists in the router.
     * 
     * @return void
     */
    public function testAddRouteMethod()
    {
        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->addRoute($httpMethod, $uri, $handler);

        $this->assertEquals(
            $this->routes[0],
            $this->router->getRoute($httpMethod, $uri)
        );
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
     * Tests if the specified routes exists in the listing of routes.
     * 
     * @return void
     */
    public function testGetRoutesMethod()
    {
        $this->router = new Router($this->routes);

        $this->assertCount(1, $this->router->getRoutes());
    }

    /**
     * Tests if the router is implemented in RouterInterface.
     * 
     * @return void
     */
    public function testRouterInterface()
    {
        $interface = 'Rougin\Slytherin\Dispatching\RouterInterface';

        $this->assertInstanceOf($interface, $this->router);
    }
}
