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
        parent::add($httpMethod, $route, $handler, $middlewares);

        // Returns the recently added route from parent ---
        $route = $this->routes[count($this->routes) - 1];
        // ------------------------------------------------

        $this->collector->addRoute($route->getMethod(), $route->getUri(), $route->getHandler());

        return $this;
    }

    /**
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return self
     */
    public function parseRoutes($routes)
    {
        foreach ($routes as $route)
        {
            $this->add($route->getMethod(), $route->getUri(), $route->getHandler(), $route->getMiddlewares());
        }

        return $this;
    }

    /**
     * @return \Phroute\Phroute\RouteDataInterface
     */
    public function getParsed()
    {
        return $this->collector->getData();
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
