<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;

/**
 * Callable Middleware Wrapper
 *
 * Converts callables into PSR-15 middlewares.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class CallableMiddlewareWrapper implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $middleware;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

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
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $middleware = $this->middleware;

        if ($this->response !== null)
        {
            $handler = function ($request) use ($handler)
            {
                return $handler->{HANDLER_METHOD}($request);
            };

            $response = $this->response;

            return $middleware($request, $response, $handler);
        }

        return $middleware($request, $handler);
    }
}
