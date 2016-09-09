<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Dispatching\DispatcherInterface;

/**
 * Dispatch Route Trait
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait DispatchRouteTrait
{
    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    private function dispatchRoute(DispatcherInterface $dispatcher, ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $path   = $request->getUri()->getPath();
        $post   = $request->getParsedBody();
        $server = $request->getServerParams();

        // For PATCH and DELETE HTTP methods
        if (isset($post['_method'])) {
            $method = strtoupper($post['_method']);
        }

        // Manually change the URI if it is in localhost
        if (in_array($server['REMOTE_ADDR'], [ '127.0.0.1', '::1' ])) {
            $search  = [ '\\', '/', DIRECTORY_SEPARATOR . 'public' ];
            $replace = [ DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, '' ];

            $cwd = str_replace($search, $replace, getcwd());
            $uri = str_replace($search, $replace, $server['DOCUMENT_ROOT'] . $path);

            $path = str_replace([ $cwd, '\\' ], [ '', '/' ], $uri);
        }

        // Gets the requested route from the dispatcher
        $route = $dispatcher->dispatch($method, $path);

        // NOTE: To be removed in v1.0.0
        if (is_array($route) && count($route) == 3) {
            $middlewares = [];

            // Extract the result into variables
            list($function, $parameters) = $route;

            // If the specified route has included a middleware
            if (isset($route[2])) {
                $middlewares = $route[2];
            }

            return [ [ $function, $parameters ], $middlewares ];
        }

        return $route;
    }
}
