<?php

namespace Rougin\Slytherin\Dispatching;

use UnexpectedValueException;

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
        $method = '';
        $className = '';
        $parameters = [];
        $middlewares = [];

        foreach ($this->router->getRoutes() as $route) {
            $hasMatch = preg_match($route[1], $uri, $parameters);

            if (! $hasMatch || $httpMethod != $route[0]) {
                continue;
            }

            if ( ! in_array($route[0], $this->validHttpMethods)) {
                $message = 'Used method is not allowed';

                throw new UnexpectedValueException($message);
            }

            array_shift($parameters);

            $parameters = array_values($parameters);

            $middlewares = $route[3];

            if (is_object($route[2])) {
                return [$route[2], $parameters, $middlewares];
            }

            list($className, $method) = $route[2];

            break;
        }

        if ( ! $className || ! $method) {
            $message = 'Route "'.$uri.'" not found';

            throw new UnexpectedValueException($message);
        }

        return [[$className, $method], $parameters, $middlewares];
    }
}
