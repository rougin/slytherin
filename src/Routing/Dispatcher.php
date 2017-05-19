<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var array
     */
    protected $allowed = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        $router == null || $this->router($router);
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
        $route = null;

        foreach ($this->routes as $item) {
            $parsed = $this->parse($httpMethod, $uri, $item);

            $parsed === null || $route = $parsed;
        }

        if (is_null($route)) {
            $message = 'Route "' . $uri . '" not found';

            throw new \UnexpectedValueException($message);
        }

        return array(array($route[0], $route[1]), $route[2]);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        foreach (array_filter($router->routes()) as $route) {
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
    protected function allowed($httpMethod)
    {
        if (! in_array($httpMethod, $this->allowed)) {
            $message = 'Used method is not allowed';

            throw new \UnexpectedValueException($message);
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
    protected function parse($httpMethod, $uri, $route)
    {
        $hasRouteMatch = preg_match($route[1], $uri, $parameters);

        $sameHttpMethod = $httpMethod == $route[0];

        if ($hasRouteMatch && $sameHttpMethod && ! empty($route[2])) {
            $this->allowed($route[0]);

            array_shift($parameters);

            return array($route[2], array_values($parameters), $route[3]);
        }

        return null;
    }
}
