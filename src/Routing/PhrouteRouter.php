<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\RouteCollector;

/**
 * Phroute Router
 *
 * A simple implementation of router that is built on top of Phroute.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/mrjgreen/phroute
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
     * @param array<int, array<int, \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[]|string>> $routes
     */
    public function __construct(array $routes = array())
    {
        $this->collector = new RouteCollector;

        parent::__construct($routes);
    }

    /**
     * Adds a new raw route.
     *
     * @param  string                                                      $method
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($method, $uri, $handler, $middlewares = array())
    {
        parent::add($method, $uri, $handler, $middlewares);

        // Returns the recently added route from parent ---
        $route = $this->routes[count($this->routes) - 1];
        // ------------------------------------------------

        $this->collector->addRoute($method, $route->getUri(), $route);

        return $this;
    }

    /**
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return \Phroute\Phroute\RouteDataArray
     */
    public function asParsed(array $routes)
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

            $this->collector->addRoute($route->getMethod(), $uri, $route);
        }

        return $this->collector->getData();
    }

    /**
     * Returns a listing of parsed routes.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return mixed|null
     */
    public function parsed(array $routes = array())
    {
        return $this->collector->getData();
    }

    /**
     * @param  \Phroute\Phroute\RouteCollector $collector
     * @return self
     */
    public function setCollector(RouteCollector $collector)
    {
        $this->collector = $collector;

        return $this;
    }
}
