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
class Router extends BaseRouter
{
    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $route) {
            list($httpMethod, $uri, $handler) = $route;

            $middlewares = (isset($route[3])) ? $route[3] : [];

            if (is_string($middlewares)) {
                $middlewares = [$middlewares];
            }

            $this->addRoute($httpMethod, $uri, $handler, $middlewares);
        }
    }

    /**
     * Returns a listing of routes available.
     * 
     * @return array
     */
    public function getRoutes()
    {
        $routes = [];

        foreach ($this->routes as $route) {
            preg_match_all('/:[a-z]*/', $route[1], $parameters);

            $pattern = str_replace($parameters[0], '(\w+)', $route[1]);
            $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

            array_push($routes, [$route[0], $pattern, $route[2], $route[3]]);
        }

        return $routes;
    }
}
