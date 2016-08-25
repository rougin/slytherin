<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased;

use Rougin\Slytherin\Dispatching\BaseRouter;

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
class Router extends BaseRouter
{
    /**
     * @var \FastRoute\RouteCollector
     */
    protected $collector;

    /**
     * @param array
     */
    public function __construct(array $routes = [])
    {
        $this->collector = new RouteCollector(new Std, new GroupCountBased);
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
     * @return callable
     */
    public function getRoutes()
    {
        $routes = array_merge($this->routes, $this->collector->getData());

        return function (RouteCollector $collector) use ($routes) {
            foreach ($this->routes as $route) {
                $collector->addRoute($route[0], $route[1], $route[2]);
            }
        };
    }
}
