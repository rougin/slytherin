<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Interface
 *
 * An interface for handling third party routers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface RouterInterface
{
    /**
     * Adds a new raw route.
     *
     * @param string|string[] $httpMethod
     * @param string          $route
     * @param mixed           $handler
     */
    public function add($httpMethod, $route, $handler);

    /**
     * Checks if the specified route is available in the router.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return boolean
     */
    public function has($httpMethod, $uri);

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param  array $routes
     * @return self
     */
    public function merge(array $routes);

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|null
     */
    public function retrieve($httpMethod, $uri);

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return mixed
     */
    public function routes($parsed = false);
}
