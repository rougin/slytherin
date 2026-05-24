<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;

/**
 * Resolves various middleware formats into a
 * MiddlewareInterface instance by Slytherin.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Resolver
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Transforms the middleware into a Slytherin counterpart.
     *
     * @param mixed $middleware
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface
     */
    public function resolve($middleware)
    {
        if ($middleware instanceof MiddlewareInterface)
        {
            return $middleware;
        }

        if ($this->isCallable($middleware))
        {
            /** @var callable $middleware */
            return new Callback($middleware, $this->response);
        }

        return new Wrapper($middleware);
    }

    /**
     * Checks if the middleware is a callable.
     *
     * @param mixed $item
     *
     * @return boolean
     */
    protected function isCallable($item)
    {
        /** @var object|string $item */
        $method = method_exists($item, '__invoke');

        $callable = is_callable($item);

        $object = is_object($item);

        $closure = (! $object) || $item instanceof \Closure;

        return ($method || $callable) && $closure;
    }
}
