<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * Dispatcher
 *
 * A simple implementation of a middleware dispatcher.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Dispatcher implements \Rougin\Slytherin\Middleware\DispatcherInterface
{
    /**
     * @var \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected $delegate;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * @param array $stack
     */
    public function __construct(array $stack = array())
    {
        $this->stack = $stack;
    }

    /**
     * Processes the specified middlewares in stack.
     * NOTE: To be removed in v1.0.0. Use MiddlewareInterface::process instead.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $stack
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array())
    {
        $this->stack = array_merge($this->stack, $stack);

        return $this->process($request, new Delegate(null, $response));
    }

    /**
     * Returns the listing of middlewares included.
     * NOTE: To be removed in v1.0.0. Use $this->stack() instead.
     *
     * @return array
     */
    public function getStack()
    {
        return $this->stack();
    }

    /**
     * Prepares and checks the middleware for specified cases.
     * NOTE: To be removed in v1.0.0. Use single pass instead.
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
            // NOTE: To be removed in v1.0.0. Use MiddlewareInterface::process instead.
            $object = new \ReflectionMethod(get_class($middleware), '__invoke');
        }

        return $this->invoke($index, $middleware, $object, $request);
    }

    /**
     * Process an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->delegate = $delegate;

        $last = function ($request) use ($delegate) {
            return $delegate->process($request);
        };

        array_push($this->stack, $last);

        $resolved = $this->resolve(0);

        return $resolved($request);
    }

    /**
     * Adds a new middleware in the stack.
     *
     * @param  callable|object|string|array $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (is_array($middleware)) {
            $this->stack = array_merge($this->stack, $middleware);

            return $this;
        }

        array_push($this->stack, $middleware);

        return $this;
    }

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function stack()
    {
        return $this->stack;
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
    protected function invoke($index, $middleware, $object, $request)
    {
        $delegate = $this->resolve($index + 1);

        if (count($object->getParameters()) == 3) { // Double pass
            // TODO: Clean this step. Where to get the response? The final response should be called last.
            // Delegate should be remove here. It should ba a callable like $next($request, $response).
            $this->response = $this->response ?: $this->delegate->process($request);

            return $middleware($request, $this->response, $delegate);
        }

        return $middleware($request, $delegate); // Single pass
    }

    /**
     * Resolves the the stack by its index.
     *
     * @param  integer $index
     * @return \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected function resolve($index)
    {
        if (isset($this->stack[$index]) === true) {
            $callable = $this->stack[$index];

            $instance = $this;

            return new Delegate(function ($request) use ($index, $callable, $instance) {
                $middleware = is_callable($callable) ? $callable : new $callable;

                // NOTE: To be removed in v1.0.0. Use single pass instead.
                return $instance->prepare($index, $middleware, $request);
            });
        }

        return new Delegate(null);
    }
}