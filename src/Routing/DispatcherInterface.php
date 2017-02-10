<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher Interface
 *
 * An interface for handling third party route dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface
{
    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array
     */
    public function dispatch($httpMethod, $uri);
}
