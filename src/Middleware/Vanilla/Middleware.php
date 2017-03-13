<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

/**
 * Vanilla Middleware
 *
 * A simple implementation of a middleware on PSR-15.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Middleware implements \Rougin\Slytherin\Middleware\MiddlewareInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * Processes the specified middlewares in stack.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $stack
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array())
    {
        $this->stack    = $stack;
        $this->response = $response;

        return $this->dispatch($request);
    }

    /**
     * Dispatches the middleware stack and returns the resulting `ResponseInterface`.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $resolved = $this->resolve(0);

        return $resolved($request);
    }

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Prepares and checks the middleware for specified cases.
     *
     * @param  integer                                                     $index
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface|callable $middleware
     * @param  \Psr\Http\Message\ServerRequestInterface                    $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function prepare($index, $middleware, ServerRequestInterface $request)
    {
        $interface = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

        if (is_object($middleware) && is_a($middleware, $interface)) {
            return $middleware->process($request, $this->resolve($index + 1));
        }

        // NOTE: To be removed in v1.0.0. Use single pass instead.
        if (is_a($middleware, 'Closure')) {
            $object = new \ReflectionFunction($middleware);
        } else {
            $object = new \ReflectionMethod(get_class($middleware), '__invoke');
        }

        return $this->getParameters($index, $middleware, $object, $request);
    }

    /**
     * Adds a new middleware in the stack.
     *
     * @param  callable|object|string $middleware
     * @return self
     */
    public function push($middleware)
    {
        array_push($this->stack, $middleware);

        return $this;
    }

    /**
     * Calls the middleware based on its defined parameters.
     * NOTE: To be removed in v1.0.0. Use single pass instead.
     *
     * @param  integer                                                     $index
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface|callable $middleware
     * @param  \ReflectionFunction|\ReflectionMethod                       $object
     * @param  \Psr\Http\Message\ServerRequestInterface                    $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getParameters($index, $middleware, $object, $request)
    {
        if (count($object->getParameters()) == 3) {
            return $middleware($request, $this->response, $this->resolve($index + 1));
        }

        return $middleware($request, $this->resolve($index + 1));
    }

    /**
     * Resolves the the stack by its index.
     *
     * @param  integer $index
     * @return \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected function resolve($index)
    {
        if (! isset($this->stack[$index])) {
            return new Delegate(function () {
            });
        }

        $instance = $this;
        $callable = $this->stack[$index];

        return new Delegate(function ($request) use ($index, $callable, $instance) {
            $middleware = is_callable($callable) ? $callable : new $callable;

            return $instance->prepare($index, $middleware, $request);
        });
    }
}
