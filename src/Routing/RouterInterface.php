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
    public function addRoute($httpMethod, $route, $handler);

    /**
     * Adds a listing of parsed routes.
     *
     * @param  array $routes
     * @return self
     */
    public function addRoutes(array $routes);

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|null
     */
    public function getRoute($httpMethod, $uri);

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return mixed
     */
    public function getRoutes($parsed = false);
}
