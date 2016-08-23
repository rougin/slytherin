<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

use Zend\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * Stratigility Middleware
 *
 * A simple implementation of middleware that is built on top of
 * Zend Framework's Stratigility.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @link    https://github.com/zendframework/zend-stratigility
 */
class Middleware implements MiddlewareInterface
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $middleware;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $middleware
     */
    public function __construct(MiddlewarePipe $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Processes the specified middlewares in queue.
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  array $queue
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(Request $request, Response $response, array $queue = [])
    {
        $middleware = $this->middleware;

        foreach ($queue as $class) {
            $middleware->pipe(new $class);
        }

        return $middleware($request, $response);
    }
}
