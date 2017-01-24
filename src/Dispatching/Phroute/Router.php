<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

use Phroute\Phroute\RouteCollector;

/**
 * Phroute Router
 *
 * A simple implementation of router that is built on top of Phroute.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Router extends \Rougin\Slytherin\Dispatching\BaseRouter
{
    /**
     * @var \Phroute\Phroute\RouteCollector
     */
    protected $collector;

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @param array $routes
     */
    public function __construct(array $routes = array())
    {
        $this->collector = new RouteCollector;
        $this->routes    = $routes;
    }

    /**
     * Sets the collector of routes.
     *
     * @param \Phroute\Phroute\RouteCollector $collector
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
        foreach ($this->routes as $route) {
            $this->collector->addRoute($route[0], $route[1], $route[2]);
        }

        return $this->collector->getData();
    }
}
