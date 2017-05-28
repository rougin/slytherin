<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;

use Rougin\Slytherin\Middleware\Delegate;

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
    const SINGLE_PASS = 0;

    const DOUBLE_PASS = 1;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * @param array                                    $stack
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(array $stack = array(), ResponseInterface $response = null)
    {
        $this->response = $response ?: new \Rougin\Slytherin\Http\Response;

        $this->stack = $stack;
    }

    /**
     * Processes the specified middlewares from stack.
     * NOTE: To be removed in v1.0.0. Use MiddlewareInterface::process instead.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $stack
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array())
    {
        $this->response = $response;

        $this->stack = (empty($this->stack)) ? $stack : $this->stack;

        $last = $this->callback(end($this->stack), $response);

        array_pop($this->stack);

        return $this->process($request, new Delegate($last));
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
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $original = $this->stack;

        $this->push(function ($request) use ($delegate) {
            return $delegate->process($request);
        });

        foreach ($this->stack as $index => $middleware) {
            $item = $this->transform($middleware);

            $this->stack[$index] = $item;
        }

        $resolved = $this->resolve(0);

        array_pop($this->stack);

        $this->stack = $original;

        return $resolved($request);
    }

    /**
     * Adds a new middleware or a list of middlewares in the stack.
     *
     * @param  callable|object|string|array $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (is_array($middleware)) {
            $stack = array_merge($this->stack, $middleware);

            $this->stack = $stack;
        } else {
            array_push($this->stack, $middleware);
        }

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
     * Checks if the approach of the specified middleware is either single or double pass.
     *
     * @param  callable|object $middleware
     * @return boolean
     */
    protected function approach($middleware)
    {
        if (is_a($middleware, 'Closure')) {
            $object = new \ReflectionFunction($middleware);
        } else {
            $object = new \ReflectionMethod(get_class($middleware), '__invoke');
        }

        return count($object->getParameters()) == 2;
    }

    /**
     * Returns the middleware as a single pass callable.
     *
     * @param  mixed             $middleware
     * @param  ResponseInterface $response
     * @return callable
     */
    protected function callback($middleware, ResponseInterface $response)
    {
        $middleware = (is_string($middleware)) ? new $middleware : $middleware;

        $callback = function ($request, $next = null) use ($middleware) {
            return $middleware($request, $next);
        };

        if ($this->approach($middleware) == self::SINGLE_PASS) {
            $callback = function ($request, $next = null) use ($middleware, $response) {
                return $middleware($request, $response, $next);
            };
        }

        return $callback;
    }

    /**
     * Resolves the whole stack through its index.
     *
     * @param  integer $index
     * @return \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected function resolve($index)
    {
        $callback = null;

        $stack = $this->stack;

        if (isset($this->stack[$index])) {
            $item = $stack[$index];

            $next = $this->resolve($index + 1);

            $callback = function ($request) use ($index, $item, $next) {
                return $item->process($request, $next);
            };
        }

        return new Delegate($callback);
    }

    /**
     * Transforms the specified middleware into a PSR-15 middleware.
     *
     * @param  mixed   $middleware
     * @param  boolean $wrap
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function transform($middleware, $wrap = true)
    {
        $middleware = is_string($middleware) ? new $middleware : $middleware;

        if (! is_a($middleware, 'Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $approach = $this->approach($middleware);

            $response = ($approach == self::SINGLE_PASS) ? $this->response : null;

            $wrapper = new CallableMiddlewareWrapper($middleware, $response);

            $middleware = ($wrap) ? $wrapper : $middleware;
        }

        return $middleware;
    }
}
