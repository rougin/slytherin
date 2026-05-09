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
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/nikic/FastRoute
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
     * @param array<integer, \Rougin\Slytherin\Routing\RouteInterface|mixed[]> $routes
     */
    public function __construct(array $routes = array())
    {
        parent::__construct($routes);

        $this->collector = new RouteCollector(new Std, new GroupCountBased);
    }

    /**
     * @param \Rougin\Slytherin\Routing\RouteInterface[] $routes
     *
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
     * @param \Rougin\Slytherin\Routing\RouteInterface[] $routes
     *
     * @return mixed|null
     */
    public function parsed(array $routes = array())
    {
        $fn = function (RouteCollector $collector) use ($routes)
        {
            foreach ($routes as $route)
            {
                // Convert the ":name" pattern into "{name}" pattern ------------------
                $uri = $route->getUri();

                $matched = preg_match_all('/\:([a-zA-Z0-9\_\-]+)/i', $uri, $matches);

                if ($matched)
                {
                    foreach ($matches[0] as $key => $item)
                    {
                        $uri = str_replace($item, '{' . $matches[1][$key] . '}', $uri);
                    }
                }
                // --------------------------------------------------------------------

                $collector->addRoute($route->getMethod(), $uri, $route);
            }
        };

        return $fn;
    }

    /**
     * @param \FastRoute\RouteCollector $collector
     *
     * @return self
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }
}
