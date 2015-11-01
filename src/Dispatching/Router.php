<?php

namespace Rougin\Slytherin\Dispatching;

use FastRoute\RouteCollector;
use Rougin\Slytherin\Dispatching\RouterInterface;

/**
 * Router
 *
 * A simple router that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Router implements RouterInterface
{
    protected $routes;

    /**
     * @param array $routes
     */
    public function __construct(array $routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * Adds a new route.
     * 
     * @param string|string[] $httpMethod
     * @param string          $route
     * @param mixed           $handler
     */
    public function addRoute($httpMethod, $route, $handler)
    {
        array_push($this->routes, [$httpMethod, $route, $handler]);

        return $this;
    }

    /**
     * Returns a listing of routes available.
     * 
     * @return mixed
     */
    public function getRoutes()
    {
        return function (RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute($route[0], $route[1], $route[2]);
            }
        };
    }
}
