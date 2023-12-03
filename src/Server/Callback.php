<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Converts callables into Slytherin middlewares.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Callback implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $middleware;

    /**
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    protected $response = null;

    /**
     * Initializes the middleware instance.
     *
     * @param callable                                 $middleware
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct($middleware, ResponseInterface $response = null)
    {
        $this->middleware = $middleware;

        $this->response = $response;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $middleware = $this->middleware;

        if (is_string($middleware))
        {
            /** @var callable */
            $middleware = new $middleware;
        }

        if (! $this->isDoublePass($middleware))
        {
            return $middleware($request, $handler);
        }

        $fn = function ($request) use ($handler)
        {
            return $handler->handle($request);
        };

        return $middleware($request, $this->response, $fn);
    }

    /**
     * @param  mixed $item
     * @return boolean
     */
    protected function isDoublePass($item)
    {
        if ($item instanceof \Closure)
        {
            $object = new \ReflectionFunction($item);
        }
        else
        {
            /** @var object|string $item */
            $object = new \ReflectionMethod($item, '__invoke');
        }

        return count($object->getParameters()) === 3;
    }
}
