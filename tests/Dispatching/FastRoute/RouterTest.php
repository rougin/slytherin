<?php

namespace Rougin\Slytherin\Test\Dispatching\FastRoute;

use Rougin\Slytherin\Dispatching\RouterInterface;
use Rougin\Slytherin\Dispatching\FastRoute\Router;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixtures\TestController;

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
        ['GET', '/', [TestController::class, 'index']],
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

        $this->assertEquals($this->routes[0], $this->router->getRoute($uri));
    }

    /**
     * Tests if the router is implemented in RouterInterface.
     * 
     * @return void
     */
    public function testRouterInterface()
    {
        $this->assertInstanceOf(RouterInterface::class, $this->router);
    }
}
