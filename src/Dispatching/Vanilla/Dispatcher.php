<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

use UnexpectedValueException;

use Rougin\Slytherin\Dispatching\RouterInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on
 * Rougin\Slytherin\Dispatching\DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Rougin\Slytherin\Dispatching\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $validHttpMethods = [
        'DELETE',
        'GET',
        'PATCH',
        'POST',
        'PUT',
    ];

    /**
     * @param \Rougin\Slytherin\Dispatching\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|string
     *
     * @throws UnexpectedValueException
     */
    public function dispatch($httpMethod, $uri)
    {
        $class       = '';
        $middlewares = [];
        $parameters  = [];

        foreach ($this->router->getRoutes() as $route) {
            $hasMatch = preg_match($route[1], $uri, $parameters);

            if (! $hasMatch || $httpMethod != $route[0]) {
                continue;
            }

            $this->isValidHttpMethod($route[0]);

            array_shift($parameters);

            $class       = $route[2];
            $middlewares = $route[3];
            $parameters  = array_values($parameters);

            break;
        }

        if (! $class) {
            throw new UnexpectedValueException("Route \"$uri\" not found");
        }

        return [ $class, $parameters, $middlewares ];
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $httpMethod
     * @return void
     */
    private function isValidHttpMethod($httpMethod)
    {
        if (! in_array($httpMethod, $this->validHttpMethods)) {
            throw new UnexpectedValueException('Used method is not allowed');
        }
    }
}
