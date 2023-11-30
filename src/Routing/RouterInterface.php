<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Interface
 *
 * An interface for handling third party routers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RouterInterface
{
    /**
     * Adds a new raw route.
     *
     * @param  string                                                        $method
     * @param  string                                                        $uri
     * @param  string|string[]                                               $handler
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($method, $uri, $handler, $middlewares = array());

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
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    public function retrieve($method, $uri);

    /**
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes();
}
