<?php declare(strict_types = 1);

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
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes();
}
