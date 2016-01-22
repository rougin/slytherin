<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\RouteCollector;
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
class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
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
     * Returns a route details based on the specified URI.
     * 
     * @param  string $uri
     * @return array|null
     */
    public function getRoute($uri)
    {
        foreach ($this->routes as $route) {
            if ($route[1] == $uri) {
                return $route;
            }
        }

        return null;
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
