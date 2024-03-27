<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Wrapper
 *
 * A middleware that converts various middlewares into its Slytherin counterpart.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Wrapper implements MiddlewareInterface
{
    /**
     * @var mixed
     */
    protected $middleware;

    /**
     * Initializes the middleware instance.
     *
     * @param mixed $middleware
     */
    public function __construct($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface      $request
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $middleware = $this->middleware;

        if (is_string($middleware))
        {
            $middleware = new $middleware;
        }

        $next = Interop::getHandler($handler);

        /** @phpstan-ignore-next-line */
        return $middleware->process($request, $next);
    }
}
