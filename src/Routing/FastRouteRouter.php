<?php

namespace Rougin\Slytherin\Routing;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

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

        $this->collector = new RouteCollector(new Std, new GroupCountBased);
    }

    /**
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return callable
     */
    public function asParsed(array $routes)
    {
        /** @var callable */
        return $this->parsed($routes);
    }

    /**
     * Returns a listing of parsed routes.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return mixed|null
     */
    public function parsed(array $routes = array())
    {
        $fn = function (RouteCollector $collector) use ($routes)
        {
            foreach ($routes as $route)
            {
                $collector->addRoute($route->getMethod(), $route->getUri(), $route);
            }
        };

        return $fn;
    }

    /**
     * @param  \FastRoute\RouteCollector $collector
     * @return self
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }
}
