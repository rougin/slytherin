<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Interface
 *
 * An interface for handling third-party routers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RouterInterface
{
    /**
     * Adds a new raw route.
     *
     * @param  string                                                      $method
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($method, $uri, $handler, $middlewares = array());

    /**
     * Adds a DELETE route.
     *
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function delete($uri, $handler, $middlewares = array());

    /**
     * Finds a specific route based on the specified HTTP method and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function find($method, $uri);

    /**
     * Adds a GET route.
     *
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function get($uri, $handler, $middlewares = array());

    /**
     * Checks if the specified route is available in the router.
     *
     * @param  string $method
     * @param  string $uri
     * @return boolean
     */
    public function has($method, $uri);

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return self
     */
    public function merge(array $routes);

    /**
     * Returns a listing of parsed routes.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface[] $routes
     * @return mixed|null
     */
    public function parsed(array $routes = array());

    /**
     * Adds a PATCH route.
     *
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function patch($uri, $handler, $middlewares = array());

    /**
     * Adds a POST route.
     *
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function post($uri, $handler, $middlewares = array());

    /**
     * Sets a prefix for the succeeding route endpoints.
     *
     * @param  string      $prefix
     * @param  string|null $namespace
     * @return self
     */
    public function prefix($prefix = '', $namespace = null);

    /**
     * Adds a PUT route.
     *
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function put($uri, $handler, $middlewares = array());

    /**
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes();
}
