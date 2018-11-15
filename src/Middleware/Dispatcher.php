<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Delegate;
use Rougin\Slytherin\Middleware\HandlerInterface;

/**
 * Dispatcher
 *
 * A simple implementation of a middleware dispatcher.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Dispatcher implements DispatcherInterface
{
    const SINGLE_PASS = false;

    const DOUBLE_PASS = true;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * Initializes the dispatcher instance.
     *
     * @param array                                    $stack
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(array $stack = array(), ResponseInterface $response = null)
    {
        $this->response = $response ?: new Response;

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
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $original = $this->stack;

        $this->push(function ($request) use ($handler) {
            return $handler->{HANDLER_METHOD}($request);
        });

        foreach ($this->stack as $index => $middleware) {
            $middleware = (is_string($middleware)) ? new $middleware : $middleware;

            $this->stack[$index] = $this->transform($middleware);
        }

        $resolved = $this->resolve(0);

        array_pop($this->stack);

        $this->stack = $original;

        return $resolved($request);
    }

    /**
     * Adds a new middleware or a list of middlewares in the stack.
     *
     * @param  \Closure|array|object|string $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (is_array($middleware)) {
            $this->stack = array_merge($this->stack, $middleware);

            return $this;
        }

        $this->stack[] = $middleware;

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
     * @param  \Closure|object $middleware
     * @return boolean
     */
    protected function approach($middleware)
    {
        if ($middleware instanceof \Closure)
        {
            $object = new \ReflectionFunction($middleware);

            return count($object->getParameters()) === 2;
        }

        $class = (string) get_class($middleware);

        $object = new \ReflectionMethod($class, '__invoke');

        return count($object->getParameters()) === 2;
    }

    /**
     * Returns the middleware as a single pass callable.
     *
     * @param  \Closure|object|string              $middleware
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Closure
     */
    protected function callback($middleware, ResponseInterface $response)
    {
        $middleware = is_string($middleware) ? new $middleware : $middleware;

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
     * @return \Rougin\Slytherin\Middleware\HandlerInterface
     */
    protected function resolve($index)
    {
        $callback = null;

        $stack = $this->stack;

        if (isset($this->stack[$index])) {
            $item = $stack[$index];

            $next = $this->resolve($index + 1);

            $callback = function ($request) use ($item, $next) {
                return $item->process($request, $next);
            };
        }

        return new Delegate($callback);
    }

    /**
     * Transforms the specified middleware into a PSR-15 middleware.
     *
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface|\Closure $middleware
     * @param  boolean                                                     $wrappable
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function transform($middleware, $wrappable = true)
    {
        if (is_a($middleware, Application::MIDDLEWARE) === false) {
            $approach = (boolean) $this->approach($middleware);

            $response = $approach === self::SINGLE_PASS ? $this->response : null;

            $wrapper = new CallableMiddlewareWrapper($middleware, $response);

            $middleware = $wrappable === true ? $wrapper : $middleware;
        }

        return $middleware;
    }
}
