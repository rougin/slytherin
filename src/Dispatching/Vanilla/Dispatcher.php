<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on
 * Rougin\Slytherin\Dispatching\DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements \Rougin\Slytherin\Dispatching\DispatcherInterface
{
    /**
     * @var \Rougin\Slytherin\Dispatching\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $validHttpMethods = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * @param \Rougin\Slytherin\Dispatching\RouterInterface $router
     */
    public function __construct(\Rougin\Slytherin\Dispatching\RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function dispatch($httpMethod, $uri)
    {
        $callback = function ($route) use ($httpMethod, $uri) {
            return $this->parseRoute($httpMethod, $uri, $route);
        };

        $routes = $this->router->getRoutes();
        $routes = array_values(array_filter(array_map($callback, $routes)));

        if (empty($routes)) {
            throw new \UnexpectedValueException("Route \"$uri\" not found");
        }

        return $routes[0];
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $httpMethod
     * @return boolean
     *
     * @throws UnexpectedValueException
     */
    private function isValidHttpMethod($httpMethod)
    {
        if (! in_array($httpMethod, $this->validHttpMethods)) {
            throw new \UnexpectedValueException('Used method is not allowed');
        }

        return true;
    }

    /**
     * Parses the specified route and make some checks.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @param  array  $route
     * @return array|null
     */
    private function parseRoute($httpMethod, $uri, $route)
    {
        $hasRouteMatch  = preg_match($route[1], $uri, $parameters);
        $sameHttpMethod = $httpMethod == $route[0];

        if (! $hasRouteMatch || ! $sameHttpMethod || empty($route[2])) {
            return null;
        }

        $this->isValidHttpMethod($route[0]);

        array_shift($parameters);

        return array($route[2], array_values($parameters), $route[3]);
    }
}
