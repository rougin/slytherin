<?php

namespace Rougin\Slytherin\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Delegate;

/**
 * Dispatcher
 *
 * A simple implementation of a middleware dispatcher.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * @var array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string>
     */
    protected $stack = array();

    /**
     * Initializes the dispatcher instance.
     *
     * @param array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string> $stack
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
     * @param  \Psr\Http\Message\ServerRequestInterface                                       $request
     * @param  \Psr\Http\Message\ResponseInterface                                            $response
     * @param  array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string> $stack
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array())
    {
        $this->response = $response;

        $this->stack = (empty($this->stack)) ? $stack : $this->stack;

        /** @var \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string */
        $last = end($this->stack);

        $last = $this->callback($last, $response);

        array_pop($this->stack);

        return $this->process($request, new Delegate($last));
    }

    /**
     * Returns the listing of middlewares included.
     * NOTE: To be removed in v1.0.0. Use $this->stack() instead.
     *
     * @return array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string>
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

        $fn = function ($request) use ($delegate)
        {
            return $delegate->process($request);
        };

        $this->push($fn);

        foreach ($this->stack as $index => $middleware)
        {
            if (is_string($middleware)) $middleware = new $middleware;

            /** @var \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface $middleware */
            $this->stack[$index] = $this->transform($middleware);
        }

        $resolved = $this->resolve(0);

        array_pop($this->stack);

        $this->stack = $original;

        return $resolved->process($request);
    }

    /**
     * Adds a new middleware or a list of middlewares in the stack.
     *
     * @param  array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string>|\Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (is_array($middleware))
        {
            $this->stack = array_merge($this->stack, $middleware);

            return $this;
        }

        array_push($this->stack, $middleware);

        return $this;
    }

    /**
     * Returns the listing of middlewares included.
     *
     * @return array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string>
     */
    public function stack()
    {
        return $this->stack;
    }

    /**
     * Checks if the approach of the specified middleware is either single or double pass.
     *
     * @param  \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface $middleware
     * @return boolean
     */
    protected function approach($middleware)
    {
        if (is_a($middleware, 'Closure'))
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
     * @param  \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string $middleware
     * @param  \Psr\Http\Message\ResponseInterface                                $response
     * @return callable
     */
    protected function callback($middleware, ResponseInterface $response)
    {
        if (is_string($middleware))
        {
            /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface */
            $middleware = new $middleware;
        }

        $fn = function ($request, $next = null) use ($middleware)
        {
            /** @var callable $middleware */
            return $middleware($request, $next);
        };

        if ($this->approach($middleware) === self::SINGLE_PASS)
        {
            $fn = function ($request, $next = null) use ($middleware, $response)
            {
                /** @var callable $middleware */
                return $middleware($request, $response, $next);
            };
        }

        return $fn;
    }

    /**
     * Resolves the whole stack through its index.
     *
     * @param  integer $index
     * @return \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected function resolve($index)
    {
        $stack = $this->stack;

        if (! isset($this->stack[$index]))
        {
            return new Delegate(null);
        }

        /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface */
        $item = $stack[(integer) $index];

        $next = $this->resolve($index + 1);

        $fn = function ($request) use ($item, $next)
        {
            return $item->process($request, $next);
        };

        return new Delegate($fn);
    }

    /**
     * Transforms the specified middleware into a PSR-15 middleware.
     *
     * @param  \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface $middleware
     * @param  boolean                                                     $wrappable
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function transform($middleware, $wrappable = true)
    {
        $response = null;

        if (is_a($middleware, Application::MIDDLEWARE)) return $middleware;

        $approach = $this->approach($middleware);

        $singlePass = $approach === self::SINGLE_PASS;

        if ($singlePass) $response = $this->response;

        $wrapper = new CallableMiddlewareWrapper($middleware, $response);

        /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface */
        return $wrappable ? $wrapper : $middleware;
    }
}
