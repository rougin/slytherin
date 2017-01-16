<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Dispatching\DispatcherInterface;

/**
 * Route Dispatcher
 *
 * Dispatches the route using DispatcherInterface and ServerRequestInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RouteDispatcher
{
    /**
     * @var \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param \Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $path   = $request->getUri()->getPath();
        $post   = $request->getParsedBody();

        // For PATCH and DELETE HTTP methods
        if (isset($post['_method'])) {
            $method = strtoupper($post['_method']);
        }

        $route = $this->dispatcher->dispatch($method, $path);

        list($function, $parameters, $middlewares) = $route;

        $result = (is_null($parameters)) ? $function : array($function, $parameters);

        return array($result, $middlewares);
    }
}
