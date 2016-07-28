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
        $classMethod = '';
        $className   = '';
        $middlewares = [];
        $parameters  = [];

        foreach ($this->router->getRoutes() as $route) {
            $hasMatch = preg_match($route[1], $uri, $parameters);

            if ( ! $hasMatch || $httpMethod != $route[0]) {
                continue;
            }

            $this->isValidHttpMethod($route[0]);

            array_shift($parameters);

            $parameters = array_values($parameters);

            $middlewares = $route[3];

            if (is_object($route[2])) {
                return [ $route[2], $parameters, $middlewares ];
            }

            list($className, $classMethod) = $route[2];

            break;
        }

        if ( ! $className || ! $classMethod) {
            throw new UnexpectedValueException("Route \"$uri\" not found");
        }

        return [ [ $className, $classMethod ], $parameters, $middlewares ];
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     * 
     * @param  string  $httpMethod
     * @return void
     */
    private function isValidHttpMethod($httpMethod)
    {
        if ( ! in_array($httpMethod, $this->validHttpMethods)) {
            throw new UnexpectedValueException('Used method is not allowed');
        }
    }
}
