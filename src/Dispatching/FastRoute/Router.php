<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\RouteCollector;

use Rougin\Slytherin\Dispatching\BaseRouter;
use Rougin\Slytherin\Dispatching\RouterInterface;

/**
 * FastRoute Router
 *
 * A simple implementation of router that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Router extends BaseRouter
{
    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Returns a listing of routes available.
     * 
     * @return callable
     */
    public function getRoutes()
    {
        return function(RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute($route[0], $route[1], $route[2]);
            }
        };
    }
}
