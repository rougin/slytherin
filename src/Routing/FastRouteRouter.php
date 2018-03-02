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
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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
     * @param array
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
     * @param \FastRoute\RouteCollector $collector
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Returns a listing of routes available.
     *
     * @return callable
     */
    public function routes()
    {
        $routes = array_merge($this->routes, $this->collector->getData());

        return function (RouteCollector $collector) use ($routes) {
            foreach (array_filter($routes) as $route) {
                list($method, $uri, $handler) = (array) $route;

                $collector->addRoute($method, $uri, $handler);
            }
        };
    }
}
