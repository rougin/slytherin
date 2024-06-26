<?php

namespace Rougin\Slytherin\Routing;

/**
 * FastRoute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of FastRoute.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/nikic/FastRoute
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
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri)
    {
        $this->validMethod($method);

        /** @var array<integer, int|string> */
        $result = $this->fastroute->dispatch($method, $uri);

        if ($result[0] === \FastRoute\Dispatcher::NOT_FOUND)
        {
            $text = (string) 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \BadMethodCallException($error);
        }

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $result[1];

        /** @var array<string, string> */
        $params = $result[2];

        return $route->setParams($params);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface $router
     *
     * @return self
     * @throws \UnexpectedValueException
     */
    public function setRouter(RouterInterface $router)
    {
        $routes = $router->routes();

        $parsed = $router->parsed($routes);

        // Force to use third-party router if not being used ---
        if (! is_callable($parsed))
        {
            $fastroute = new FastRouteRouter;

            $parsed = $fastroute->asParsed($routes);
        }
        // -----------------------------------------------------

        $this->router = $router;

        $this->fastroute = \FastRoute\simpleDispatcher($parsed);

        return $this;
    }
}
