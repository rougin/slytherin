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
class Middleware extends \Rougin\Slytherin\Middleware\BaseMiddleware implements \Rougin\Slytherin\Middleware\MiddlewareInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Processes the specified middlewares in queue.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $queue
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $queue = array())
    {
        $this->queue    = $queue;
        $this->response = $response;

        return $this->dispatch($request);
    }

    /**
     * Dispatches the middleware queue and returns the resulting `ResponseInterface`.
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
     * Prepares and checks the middleware for specified cases.
     *
     * @param  integer                                                    $index
     * @param  Interop\Http\ServerMiddleware\MiddlewareInterface|callable $middleware
     * @param  \Psr\Http\Message\ServerRequestInterface                   $request
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function prepare($index, $middleware, $request)
    {
        if ($middleware instanceof \Closure) {
            $object = new \ReflectionFunction($middleware);
        } else {
            $object = new \ReflectionMethod(get_class($middleware), '__invoke');
        }

        // NOTE: To be removed in v1.0.0
        if (count($object->getParameters()) == 3) {
            return $middleware($request, $this->response, $this->resolve($index + 1));
        }

        return $middleware($request, $this->resolve($index + 1));
    }

    /**
     * Resolves the the queue by its index.
     *
     * @param  integer $index
     * @return \Interop\Http\ServerMiddleware\DelegateInterface
     */
    protected function resolve($index)
    {
        if (! isset($this->queue[$index])) {
            return new Delegate(function () {
            });
        }

        $instance   = $this;
        $middleware = $this->queue[$index];

        $callable = function ($request) use ($index, $middleware, $instance) {
            $middleware = is_callable($middleware) ? $middleware : new $middleware;

            if ($middleware instanceof MiddlewareInterface) {
                return $middleware->process($request, $this->resolve($index + 1));
            }

            return $instance->prepare($index, $middleware, $request);
        };

        return new Delegate($callable);
    }
}
