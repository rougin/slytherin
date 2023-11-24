<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var string[]
     */
    protected $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

    /**
     * @var array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>>
     */
    protected $routes = array();

    /**
     * Initializes the route dispatcher instance.
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
     * @return array<int, mixed>
     */
    public function dispatch($httpMethod, $uri)
    {
        /** @var array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null> */
        $routes = array();

        foreach ($this->routes as $route)
        {
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
        /** @var array<int, array<int, mixed>> */
        $routes = $router->routes();

        $fn = function ($item)
        {
            return str_replace(':', '', $item);
        };

        /** @var array<int, string> $route */
        foreach (array_filter($routes) as $route)
        {
            preg_match_all('/:[a-z]*/', $route[1], $result);

            $route[4] = str_replace($result[0], '(\w+)', $route[1]);
            $route[4] = '/^' . str_replace('/', '\/', $route[4]) . '$/';
            $route[5] = array_map($fn, $result[0]);

            array_push($this->routes, (array) $route);
        }

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
        if (! in_array($httpMethod, $this->allowed))
        {
            $message = 'Used method is not allowed';

            throw new \UnexpectedValueException($message);
        }

        return true;
    }

    /**
     * Parses the specified route and make some checks.
     *
     * @param  string                                                                           $httpMethod
     * @param  string                                                                           $uri
     * @param  array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string> $route
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null
     */
    protected function parse($httpMethod, $uri, $route)
    {
        /** @var string $route[4] */
        $matched = preg_match($route[4], $uri, $parameters);

        if (! $matched) return null;

        if ($httpMethod != $route[0] && $httpMethod != 'OPTIONS')
        {
            return null;
        }

        $this->allowed($route[0]);

        array_shift($parameters);

        return array($route[2], $parameters, $route[3], $route[5]);
    }

    /**
     * Retrieved the specified route from the result.
     * 
     * @param  array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null> $routes
     * @param  string $uri
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>
     * 
     * @throws \UnexpectedValueException
     */
    protected function retrieve(array $routes, $uri)
    {
        $routes = array_values(array_filter($routes));

        if (empty($routes))
        {
            $message = 'Route "' . $uri . '" not found';

            throw new \UnexpectedValueException($message);
        }

        /** @var array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string> */
        $route = current($routes);

        /** @var string[] */
        $params = $route[1];

        if (count($params) > 0)
        {
            /** @var string[] */
            $regex = $route[3];

            /** @var string[] $route[1] */
            $route[1] = array_combine($regex, $params);
        }

        return (array) $route;
    }
}