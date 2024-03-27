<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router
 *
 * A simple implementation of a router that is based on RouterInterface.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param array<integer, \Rougin\Slytherin\Routing\RouteInterface|mixed[]> $routes
     */
    public function __construct(array $routes = array())
    {
        // Set prefix conditions if above is defined ---
        $this->prefix($this->prefix, $this->namespace);
        // ---------------------------------------------

        foreach ($routes as $route)
        {
            // Add it directly to list of routes if it's already a RouteInterface ---
            if ($route instanceof RouteInterface)
            {
                array_push($this->routes, $route);

                continue;
            }
            // ----------------------------------------------------------------------

            /** @var string */
            $method = $route[0];

            /** @var string */
            $uri = $route[1];

            /** @var callable|string|string[] */
            $handler = $route[2];

            /** @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string|string[] */
            $middlewares = isset($route[3]) ? $route[3] : array();

            if (is_string($middlewares))
            {
                $middlewares = array($middlewares);
            }

            $this->add($method, $uri, $handler, $middlewares);
        }
    }

    /**
     * Adds a new raw route.
     *
     * @param string                   $method
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
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
     * @deprecated since ~0.9, use "add" instead.
     *
     * Adds a new raw route.
     *
     * @param string                   $method
     * @param string                   $route
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function addRoute($method, $route, $handler, $middlewares = array())
    {
        return $this->add($method, $route, $handler, $middlewares);
    }

    /**
     * @deprecated since ~0.9, use "merge" instead.
     *
     * Merges a listing of parsed routes to current one.
     *
     * @param \Rougin\Slytherin\Routing\RouteInterface[] $routes
     *
     * @return self
     */
    public function addRoutes(array $routes)
    {
        return $this->merge($routes);
    }

    /**
     * Adds a DELETE route.
     *
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function delete($uri, $handler, $middlewares = array())
    {
        return $this->add('DELETE', $uri, $handler, $middlewares);
    }

    /**
     * Finds a specific route based on the specified HTTP method and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function find($method, $uri)
    {
        foreach ($this->routes as $route)
        {
            $sameMethod = $route->getMethod() === $method;

            $sameUri = $route->getUri() === $uri;

            if (! $sameMethod || ! $sameUri)
            {
                continue;
            }

            return $route;
        }

        return null;
    }

    /**
     * Adds a GET route.
     *
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function get($uri, $handler, $middlewares = array())
    {
        return $this->add('GET', $uri, $handler, $middlewares);
    }

    /**
     * @deprecated since ~0.9, use "retrieve" instead.
     *
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function getRoute($method, $uri)
    {
        return $this->retrieve($method, $uri);
    }

    /**
     * @deprecated since ~0.9, use "routes" instead.
     *
     * Returns a listing of available routes.
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
     * @param string $method
     * @param string $uri
     *
     * @return boolean
     */
    public function has($method, $uri)
    {
        return $this->retrieve($method, $uri) !== null;
    }

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param \Rougin\Slytherin\Routing\RouteInterface[] $routes
     *
     * @return self
     */
    public function merge(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);

        return $this;
    }

    /**
     * Returns a listing of parsed routes.
     *
     * @param \Rougin\Slytherin\Routing\RouteInterface[] $routes
     *
     * @return mixed|null
     */
    public function parsed(array $routes = array())
    {
        return null;
    }

    /**
     * Adds a PATCH route.
     *
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function patch($uri, $handler, $middlewares = array())
    {
        return $this->add('PATCH', $uri, $handler, $middlewares);
    }

    /**
     * Adds a POST route.
     *
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function post($uri, $handler, $middlewares = array())
    {
        return $this->add('POST', $uri, $handler, $middlewares);
    }

    /**
     * Sets a prefix for the succeeding route endpoints.
     *
     * @param string      $prefix
     * @param string|null $namespace
     *
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
     * Adds a PUT route.
     *
     * @param string                   $uri
     * @param callable|string|string[] $handler
     * @param callable|mixed[]|string  $middlewares
     *
     * @return self
     */
    public function put($uri, $handler, $middlewares = array())
    {
        return $this->add('PUT', $uri, $handler, $middlewares);
    }

    /**
     * Adds a listing of routes specified for RESTful approach.
     *
     * @param string         $uri
     * @param string         $class
     * @param mixed[]|string $middlewares
     *
     * @return self
     */
    public function restful($uri, $class, $middlewares = array())
    {
        $this->get('/' . $uri, $class . '@index', $middlewares);
        $this->post('/' . $uri, $class . '@store', $middlewares);

        $this->delete('/' . $uri . '/:id', $class . '@delete', $middlewares);
        $this->get('/' . $uri . '/:id', $class . '@show', $middlewares);
        $this->patch('/' . $uri . '/:id', $class . '@update', $middlewares);
        $this->put('/' . $uri . '/:id', $class . '@update', $middlewares);

        return $this;
    }

    /**
     * @deprecated since ~0.9, use "find" instead.
     *
     * Finds a specific route based on the specified HTTP method and URI.
     *
     * @param string $method
     * @param string $uri
     *
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
     * @deprecated since ~0.9, use "prefix" instead.
     *
     * Sets a prefix for the succeeding route endpoints.
     *
     * @param string $prefix
     * @param string $namespace
     *
     * @return self
     */
    public function setPrefix($prefix = '', $namespace = '')
    {
        return $this->prefix($prefix, $namespace);
    }
}
