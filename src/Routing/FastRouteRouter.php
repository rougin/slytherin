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
     * @param array
     */
    public function __construct(array $routes = array())
    {
        $count = new \FastRoute\DataGenerator\GroupCountBased;
        $std   = new \FastRoute\RouteParser\Std;

        $this->collector = new RouteCollector($std, $count);
        $this->routes    = $routes;
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
     * @param  boolean $parsed
     * @return callable
     */
    public function routes($parsed = false)
    {
        $routes = array_filter(array_merge($this->routes, $this->collector->getData()));

        if ($parsed === true) {
            $routes = function (RouteCollector $collector) use ($routes) {
                foreach ($routes as $route) {
                    $collector->addRoute($route[0], $route[1], $route[2]);
                }
            };
        }

        return $routes;
    }
}
