<?php

namespace Rougin\Slytherin\Routing;

use FastRoute\RouteCollector;

/**
 * FastRoute Router
 *
 * A simple implementation of router that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteRouter extends Router
{
    /**
     * @var \FastRoute\RouteCollector
     */
    protected $collector;

    /**
     * Initializes the router instance.
     *
     * @param array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     */
    public function __construct(array $routes = array())
    {
        parent::__construct($routes);

        $count = new \FastRoute\DataGenerator\GroupCountBased;

        $std = new \FastRoute\RouteParser\Std;

        $this->collector = new RouteCollector($std, $count);
    }

    /**
     * Sets the collector of routes.
     *
     * @param  \FastRoute\RouteCollector $collector
     * @return self
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return array<int, mixed>|callable
     */
    public function routes($parsed = false)
    {
        $data = $this->collector->getData();

        $routes = array_filter(array_merge($this->routes, $data));

        if (! $parsed) return $routes;

        return function (RouteCollector $collector) use ($routes)
        {
            foreach ($routes as $route)
            {
                $collector->addRoute($route[0], $route[1], $route[2]);
            }
        };
    }
}