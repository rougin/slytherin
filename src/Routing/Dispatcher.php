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
    protected $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * Initializes the route dispatcher instance.
     *
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
     * @return array|mixed
     */
    public function dispatch($httpMethod, $uri)
    {
        $routes = array();

        foreach ($this->routes as $route) {
            $parsed = $this->parse($httpMethod, $uri, $route);

            array_push($routes, $parsed);
        }

        $route = $this->retrieve($routes, $uri);

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

            $route[4] = str_replace($parameters[0], '(\w+)', $route[1]);
            $route[4] = '/^' . str_replace('/', '\/', $route[4]) . '$/';

            $route[5] = array_map(function ($item) {
                return str_replace(':', '', $item);
            }, $parameters[0]);

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
        $matched = preg_match($route[4], $uri, $parameters);

        if ($matched && ($httpMethod == $route[0] || $httpMethod == 'OPTIONS')) {
            $this->allowed($route[0]);

            array_shift($parameters);

            return array($route[2], $parameters, $route[3], $route[5]);
        }

        return null;
    }

    /**
     * Retrieved the specified route from the result.
     *
     * @throws \UnexpectedValueException
     *
     * @param  array  $routes
     * @param  string $uri
     * @return array
     */
    protected function retrieve(array $routes, $uri)
    {
        $routes = array_values(array_filter($routes));

        if (empty($routes)) {
            $message = 'Route "' . $uri . '" not found';

            throw new \UnexpectedValueException($message);
        }

        $route = current($routes);

        $route[1] = (count($route[1]) > 0) ? array_combine($route[3], $route[1]) : $route[1];

        return $route;
    }
}
