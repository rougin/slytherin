<?php

namespace Rougin\Slytherin\Routing;

use FastRoute\RouteCollector;

/**
 * FastRoute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteDispatcher extends Dispatcher
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastroute;

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface
     *
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri)
    {
        $this->validMethod($method);

        /** @var array<int, int|string> */
        $result = $this->fastroute->dispatch($method, $uri);

        if ($result[0] === \FastRoute\Dispatcher::NOT_FOUND)
        {
            $text = (string) 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \BadMethodCallException($error);
        }

        // Need only to find the Route instance ----
        $route = $this->router->find($method, $uri);
        // -----------------------------------------

        return $route->setParams($result[2]);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function setRouter(RouterInterface $router)
    {
        $routes = $router->routes();

        $fn = function (RouteCollector $collector) use ($routes)
        {
            foreach ($routes as $route)
            {
                $collector->addRoute($route->getMethod(), $route->getUri(), $route->getHandler());
            }
        };

        $this->router = $router;

        $this->fastroute = \FastRoute\simpleDispatcher($fn);

        return $this;
    }
}
