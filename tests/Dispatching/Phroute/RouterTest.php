<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Phroute\Phroute\RouteCollector;
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
        // Create a fresh router without a collector ---
        $this->self = new Router;
        // ---------------------------------------------

        // Verify parsed routes return the data array ---
        /** @var class-string<\Phroute\Phroute\RouteDataArray> */
        $expect = 'Phroute\Phroute\RouteDataArray';

        $actual = $this->self->parsed();

        $this->assertInstanceOf($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->checkIfPhrouteExists();

        // Generate a sample route for testing -----------
        $route = new Route('GET', '/', $class . '@index');

        $this->routes[] = $route;
        // -----------------------------------------------

        $this->self = new Router;

        $this->self->setCollector(new RouteCollector);
    }
}
