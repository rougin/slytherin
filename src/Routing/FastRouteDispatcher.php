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
class FastRouteDispatcher implements DispatcherInterface
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * Initializes the dispatcher instance.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        if ($router) $this->router($router);
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|mixed
     */
    public function dispatch($httpMethod, $uri)
    {
        $this->allowed($httpMethod);

        /** @var array<int, string> */
        $result = $this->dispatcher->dispatch($httpMethod, $uri);

        if ($result[0] == \FastRoute\Dispatcher::NOT_FOUND)
        {
            $message = 'Route "' . $uri . '" not found';

            throw new \UnexpectedValueException($message);
        }

        $route = $this->router->retrieve($httpMethod, (string) $uri);

        $sameRoute = isset($route[2]) && $route[2] === $result[1];

        $hasMiddleware = isset($route[3]);

        $middlewares = $sameRoute && $hasMiddleware ? $route[3] : array();

        return array(array($result[1], $result[2]), $middlewares);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        $this->router = $router;

        if ($router instanceof FastRouteRouter)
        {
            /** @var callable */
            $routes = $router->routes(true);

            $this->dispatcher = \FastRoute\simpleDispatcher($routes);

            return $this;
        }

        $fn = function (RouteCollector $collector) use ($router)
        {
            /** @var array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> */
            $routes = $router->routes();

            foreach (array_filter($routes) as $route)
            {
                /** @var string */
                $method = $route[0];

                /** @var string */
                $uri = $route[1];

                /** @var string[]|string */
                $handler = $route[2];

                $collector->addRoute($method, $uri, $handler);
            }
        };

        $this->dispatcher = \FastRoute\simpleDispatcher($fn);

        return $this;
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $httpMethod
     * @return boolean
     *
     * @throws \UnexpectedValueException
     */
    protected function allowed($httpMethod)
    {
        $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

        if (in_array($httpMethod, $allowed) === false)
        {
            $message = 'Used method is not allowed';

            throw new \UnexpectedValueException($message);
        }

        return true;
    }
}
