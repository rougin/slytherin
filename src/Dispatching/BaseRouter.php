<?php

namespace Rougin\Slytherin\Dispatching;

/**
 * Dispatcher
 *
 * A simple implementation of a router that is based on
 * Rougin\Slytherin\Dispatching\RouterInterface.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class BaseRouter implements RouterInterface
{
    /**
     * @var array
     */
    protected $routes = [];

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
     * Returns a route details based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|null
     */
    public function getRoute($httpMethod, $uri)
    {
        $result = null;

        foreach ($this->routes as $route) {
            if ($route[0] == $httpMethod && $route[1] == $uri) {
                $result = $route;

                break;
            }
        }

        return $result;
    }

    /**
     * Returns a listing of routes available.
     * 
     * @return array|callable
     */
    abstract public function getRoutes();
}
