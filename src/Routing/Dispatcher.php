<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on
 * Rougin\Slytherin\Routing\DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $validHttpMethods = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        $router == null || $this->setRouter($router);
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
        $routes = array();

        foreach ($this->routes as $route) {
            $parsed = $this->parseRoute($httpMethod, $uri, $route);

            array_push($routes, $parsed);
        }

        $routes = array_values(array_filter($routes));

        if (empty($routes)) {
            throw new \UnexpectedValueException("Route \"$uri\" not found");
        }

        return $routes[0];
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function setRouter(RouterInterface $router)
    {
        $routes = array_filter($router->getRoutes());

        foreach ($routes as $route) {
            preg_match_all('/:[a-z]*/', $route[1], $parameters);

            $route[1] = str_replace($parameters[0], '(\w+)', $route[1]);
            $route[1] = '/^' . str_replace('/', '\/', $route[1]) . '$/';

            array_push($this->routes, $route);
        }

        return $this;
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
