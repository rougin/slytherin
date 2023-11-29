<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\RouteCollector;

/**
 * Phroute Router
 *
 * A simple implementation of router that is built on top of Phroute.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteRouter extends Router
{
    /**
     * @var \Phroute\Phroute\RouteCollector
     */
    protected $collector;

    /**
     * Initializes the router instance.
     *
     * @param array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     */
    public function __construct(array $routes = array())
    {
        $this->collector = new RouteCollector;

        parent::__construct($routes);
    }

    /**
     * Adds a new raw route.
     *
     * @param  string                                                        $httpMethod
     * @param  string                                                        $route
     * @param  string|string[]                                               $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($httpMethod, $route, $handler, $middlewares = array())
    {
        $item = array($httpMethod, $route, $handler, $middlewares);

        $route = $this->parse($item);

        $this->collector->addRoute($httpMethod, $route[1], $route[2]);

        array_push($this->routes, $route);

        return $this;
    }

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return array<int, array<int, mixed>>|mixed
     */
    public function routes($parsed = false)
    {
        $routes = $this->routes;

        return ($parsed) ? $this->collector->getData() : $routes;
    }

    /**
     * Sets the collector of routes.
     *
     * @param  \Phroute\Phroute\RouteCollector $collector
     * @return self
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }
}
