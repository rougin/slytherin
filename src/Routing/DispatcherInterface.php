<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher Interface
 *
 * An interface for handling third party route dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface
{
    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface
     *
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri);

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function setRouter(RouterInterface $router);
    // public function router(RouterInterface $router);
}
