<?php

namespace Rougin\Slytherin\Routing;

use Interop\Http\ServerMiddleware\MiddlewareInterface;

/**
 * Dispatcher
 *
 * A simple implementation of a router that is based on RouterInterface.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Router implements RouterInterface
{
    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>>
     */
    protected $routes = array();

    /**
     * @var string[]
     */
    protected $allowed = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * Initializes the router instance.
     *
     * @param array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     */
    public function __construct(array $routes = array())
    {
        foreach ($routes as $route)
        {
            /** @var string */
            $httpMethod = $route[0];

            /** @var string */
            $uri = $route[1];

            /** @var string[]|string */
            $handler = $route[2];

            /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string */
            $middlewares = isset($route[3]) ? $route[3] : array();

            if (is_string($middlewares)) $middlewares = array($middlewares);

            $this->add($httpMethod, $uri, $handler, $middlewares);
        }
    }

    /**
     * Adds a new raw route.
     *
     * @param  string                                                        $httpMethod
     * @param  string                                                        $route
     * @param  string|string[]                                               $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($httpMethod, $route, $handler, $middlewares = array())
    {
        $route = array($httpMethod, $route, $handler, $middlewares);

        $this->routes[] = $this->parse($route);

        return $this;
    }

    /**
     * Adds a new raw route.
     * NOTE: To be removed in v1.0.0. Use $this->add() instead.
     *
     * @param  string                                                        $httpMethod
     * @param  string                                                        $route
     * @param  string|string[]                                               $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function addRoute($httpMethod, $route, $handler, $middlewares = array())
    {
        return $this->add($httpMethod, $route, $handler, $middlewares);
    }

    /**
     * Merges a listing of parsed routes to current one.
     * NOTE: To be removed in v1.0.0. Use $this->merge() instead.
     *
     * @param  array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     * @return self
     */
    public function addRoutes(array $routes)
    {
        return $this->merge($routes);
    }

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     * NOTE: To be removed in v1.0.0. Use $this->retrieve() instead.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null
     */
    public function getRoute($httpMethod, $uri)
    {
        return $this->retrieve($httpMethod, $uri);
    }

    /**
     * Returns a listing of available routes.
     * NOTE: To be removed in v1.0.0. Use $this->routes() instead.
     *
     * @param  boolean $parsed
     * @return array<int, array<int, mixed>>|mixed
     */
    public function getRoutes($parsed = false)
    {
        return $this->routes($parsed);
    }

    /**
     * Checks if the specified route is available in the router.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return boolean
     */
    public function has($httpMethod, $uri)
    {
        return $this->retrieve($httpMethod, $uri) !== null;
    }

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param  array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     * @return self
     */
    public function merge(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);

        return $this;
    }

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null
     */
    public function retrieve($httpMethod, $uri)
    {
        $route = array($httpMethod, $uri);

        $fn = function ($route)
        {
            return array($route[0], $route[1]);
        };

        $routes = array_map($fn, $this->routes);

        $key = array_search($route, $routes);

        if ($key === false) return null;

        return $this->routes[(integer) $key];
    }

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return array<int, array<int, mixed>>|mixed
     */
    public function routes($parsed = false)
    {
        return $this->routes;
    }

    /**
     * Adds a listing of routes specified for RESTful approach.
     *
     * @param  string          $route
     * @param  string          $class
     * @param  string|string[] $middlewares
     * @return self
     */
    public function restful($route, $class, $middlewares = array())
    {
        $middlewares = (is_string($middlewares)) ? array($middlewares) : $middlewares;

        $this->add('GET', '/' . $route, $class . '@index', $middlewares);
        $this->add('POST', '/' . $route, $class . '@store', $middlewares);

        $this->add('DELETE', '/' . $route . '/:id', $class . '@delete', $middlewares);
        $this->add('GET', '/' . $route . '/:id', $class . '@show', $middlewares);
        $this->add('PATCH', '/' . $route . '/:id', $class . '@update', $middlewares);
        $this->add('PUT', '/' . $route . '/:id', $class . '@update', $middlewares);

        return $this;
    }

    /**
     * Sets a prefix for the succeeding route endpoints.
     *
     * @param  string      $prefix
     * @param  string|null $namespace
     * @return self
     */
    public function prefix($prefix = '', $namespace = null)
    {
        $this->namespace = ($namespace !== null) ? $namespace . '\\' : $this->namespace;

        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Sets a prefix for the succeeding route endpoints.
     * NOTE: To be removed in v1.0.0. Use $this->prefix() instead.
     *
     * @param  string $prefix
     * @param  string $namespace
     * @return self
     */
    public function setPrefix($prefix = '', $namespace = '')
    {
        return $this->prefix($prefix, $namespace);
    }

    /**
     * Parses the route.
     *
     * @param  array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string> $route
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>
     */
    protected function parse($route)
    {
        if (is_string($route[0])) $route[0] = strtoupper($route[0]);

        if (is_string($route[1]))
        {
            $route[1] = str_replace('//', '/', $this->prefix . $route[1]);
        }

        if (is_string($route[2])) $route[2] = explode('@', $route[2]);

        if (! is_array($route[3])) $route[3] = array($route[3]);

        if (is_array($route[2]))
        {
            /** @var string */
            $method = $route[2][0];

            $route[2][0] = $this->namespace . $method;
        }

        return $route;
    }

    /**
     * Calls methods from this class in HTTP method format.
     *
     * @param  string  $method
     * @param  mixed[] $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = (string) strtoupper($method);

        if (! in_array($method, $this->allowed))
        {
            $text = "\"$method\" is not a valid HTTP method";

            throw new \BadMethodCallException($text);
        }

        array_unshift($parameters, $method);

        return call_user_func_array(array($this, 'add'), $parameters);
    }
}