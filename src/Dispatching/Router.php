<?php

namespace Rougin\Slytherin\Dispatching;

use Rougin\Slytherin\Dispatching\RouterInterface;

/**
 * Dispatcher
 *
 * A simple implementation of a router that is based on
 * Rougin\Slytherin\Dispatching\RouterInterface.
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
        foreach ($routes as $route) {
            $this->addRoute($route[0], $route[1], $route[2]);
        }
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
        preg_match('/:[a-z]*/', $route, $parameters);

        $pattern = str_replace($parameters, '(\w+)', $route);
        $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

        array_push($this->routes, [$httpMethod, $pattern, $handler]);

        return $this;
    }

    /**
     * Returns a listing of routes available.
     * 
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
