<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router
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
     * @var \Rougin\Slytherin\Routing\RouteInterface[]
     */
    protected $routes = array();

    /**
     * Initializes the router instance.
     *
     * @param array<int, array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>> $routes
     */
    public function __construct(array $routes = array())
    {
        // Set prefix conditions if above is defined ---
        $this->prefix($this->prefix, $this->namespace);
        // ---------------------------------------------

        foreach ($routes as $route)
        {
            /** @var string */
            $method = $route[0];

            /** @var string */
            $uri = $route[1];

            /** @var string[]|string */
            $handler = $route[2];

            /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string */
            $middlewares = isset($route[3]) ? $route[3] : array();

            if (is_string($middlewares)) $middlewares = array($middlewares);

            $this->add($method, $uri, $handler, $middlewares);
        }
    }

    /**
     * Adds a new raw route.
     *
     * @param  string                                                        $method
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($method, $uri, $handler, $middlewares = array())
    {
        $method = strtoupper($method);

        $uri = str_replace('//', '/', $this->prefix . $uri);

        if (is_string($handler))
        {
            /** @var string[] */
            $handler = explode('@', $handler);
        }

        if (is_array($handler))
        {
            $handler[0] = $this->namespace . $handler[0];
        }

        if (! is_array($middlewares))
        {
            $middlewares = array($middlewares);
        }

        $route = new Route($method, $uri, $handler, $middlewares);

        array_push($this->routes, $route);

        return $this;
    }

    /**
     * Adds a new raw route.
     * NOTE: To be removed in v1.0.0. Use $this->add() instead.
     *
     * @param  string                                                        $method
     * @param  string                                                        $route
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function addRoute($method, $route, $handler, $middlewares = array())
    {
        return $this->add($method, $route, $handler, $middlewares);
    }

    /**
     * Merges a listing of parsed routes to current one.
     * NOTE: To be removed in v1.0.0. Use $this->merge() instead.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return self
     */
    public function addRoutes(array $routes)
    {
        return $this->merge($routes);
    }

    /**
     * Adds a DELETE route.
     *
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function delete($uri, $handler, $middlewares = array())
    {
        return $this->add('DELETE', $uri, $handler, $middlewares);
    }

    /**
     * Finds a specific route based on the specified HTTP method and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function find($method, $uri)
    {
        foreach ($this->routes as $route)
        {
            $sameMethod = $route->getMethod() === $method;

            $sameUri = $route->getUri() === $uri;

            if (! $sameMethod || ! $sameUri) continue;

            return $route;
        }

        return null;
    }

    /**
     * Adds a GET route.
     *
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function get($uri, $handler, $middlewares = array())
    {
        return $this->add('GET', $uri, $handler, $middlewares);
    }

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     * NOTE: To be removed in v1.0.0. Use $this->retrieve() instead.
     *
     * @param  string $method
     * @param  string $uri
     * @return array<int, \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]|string>|null
     */
    public function getRoute($method, $uri)
    {
        return $this->retrieve($method, $uri);
    }

    /**
     * Returns a listing of available routes.
     * NOTE: To be removed in v1.0.0. Use $this->routes() instead.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function getRoutes()
    {
        return $this->routes();
    }

    /**
     * Checks if the specified route is available in the router.
     *
     * @param  string $method
     * @param  string $uri
     * @return boolean
     */
    public function has($method, $uri)
    {
        return $this->retrieve($method, $uri) !== null;
    }

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return self
     */
    public function merge(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);

        return $this;
    }

    /**
     * Adds a PATCH route.
     *
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function patch($uri, $handler, $middlewares = array())
    {
        return $this->add('PATCH', $uri, $handler, $middlewares);
    }

    /**
     * Adds a POST route.
     *
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function post($uri, $handler, $middlewares = array())
    {
        return $this->add('POST', $uri, $handler, $middlewares);
    }

    /**
     * Adds a PUT route.
     *
     * @param  string                                                        $uri
     * @param  callable|string[]|string                                      $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function put($uri, $handler, $middlewares = array())
    {
        return $this->add('PUT', $uri, $handler, $middlewares);
    }

    /**
     * Finds a specific route based on the specified HTTP method and URI.
     * NOTE: To be removed in v1.0.0. Use $this->find() instead.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function retrieve($method, $uri)
    {
        return $this->find($method, $uri);
    }

    /**
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * Adds a listing of routes specified for RESTful approach.
     *
     * @param  string          $uri
     * @param  class-string    $class
     * @param  string|string[] $middlewares
     * @return self
     */
    public function restful($uri, $class, $middlewares = array())
    {
        $middlewares = (is_string($middlewares)) ? array($middlewares) : $middlewares;

        $this->add('GET', '/' . $uri, $class . '@index', $middlewares);
        $this->add('POST', '/' . $uri, $class . '@store', $middlewares);

        $this->add('DELETE', '/' . $uri . '/:id', $class . '@delete', $middlewares);
        $this->add('GET', '/' . $uri . '/:id', $class . '@show', $middlewares);
        $this->add('PATCH', '/' . $uri . '/:id', $class . '@update', $middlewares);
        $this->add('PUT', '/' . $uri . '/:id', $class . '@update', $middlewares);

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
        $this->prefix = $prefix;

        if ($namespace)
        {
            $namespace = $namespace . '\\';

            $namespace = str_replace('\\\\', '\\', $namespace);

            $this->namespace = $namespace;
        }

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
}
