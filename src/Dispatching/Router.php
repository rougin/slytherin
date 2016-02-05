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
            $this->addRoute($route[0], $route[1], $route[2]);
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
            preg_match('/:[a-z]*/', $route[1], $parameters);

            $pattern = str_replace($parameters, '(\w+)', $route[1]);
            $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

            array_push($routes, [$route[0], $pattern, $route[2]]);
        }

        return $routes;
    }
}
