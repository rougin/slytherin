<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Rougin\Slytherin\Dispatching\RouterTestCases;
use Rougin\Slytherin\Routing\Route;

/**
 * @deprecated since ~0.9, use "Routing\FastRouteRouterTest" instead.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    public function test_passed_if_parsed_routes_as_closure()
    {
        // Create a fresh router without a collector ---
        $this->self = new Router;
        // ---------------------------------------------

        // Verify parsed routes return a Closure for FastRoute ---
        $this->assertInstanceOf('Closure', $this->self->parsed());
        // --------------------------------------------------------
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

        $this->self = new Router;

        $this->self->setCollector($collector);
    }
}
