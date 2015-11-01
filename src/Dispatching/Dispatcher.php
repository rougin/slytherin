<?php

namespace Rougin\Slytherin\Dispatching;

use FastRoute\Dispatcher as FastRouteDispatcher;
use Rougin\Slytherin\Dispatching\DispatcherInterface;
use Rougin\Slytherin\Http\ResponseInterface as Response;
use Rougin\Slytherin\Dispatching\RouterInterface as Router;

/**
 * Dispatcher
 *
 * A simple dispatcher that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    protected $dispatcher;
    protected $response;
    protected $router;

    /**
     * @param Router   $router
     * @param Response $response
     */
    public function __construct(Router $router, Response $response)
    {
        $this->router = $router;
        $this->response = $response;

        $this->dispatcher = \FastRoute\simpleDispatcher($router->getRoutes());
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     * 
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|string
     */
    public function dispatch($httpMethod, $uri)
    {
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRouteDispatcher::NOT_FOUND:
                $this->response->setContent('404 - Page not found');
                $this->response->setStatusCode(404);

                return $this->response->getContent();
            case FastRouteDispatcher::METHOD_NOT_ALLOWED:
                $this->response->setContent('405 - Method not allowed');
                $this->response->setStatusCode(405);

                return $this->response->getContent();
            case FastRouteDispatcher::FOUND:
                $isClosure = $routeInfo[1] instanceof Closure;

                if (is_object($routeInfo[1]) && $isClosure) {
                    $handler = $routeInfo[1];
                    $parameters = $routeInfo[2];

                    return call_user_func($handler, $parameters);
                }

                $className = $routeInfo[1][0];
                $method = $routeInfo[1][1];
                $parameters = $routeInfo[2];
        }

        return [$className, $method, $parameters];
    }
}
