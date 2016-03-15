<?php

namespace Rougin\Slytherin\Middleware;

use Relay\RelayBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Relay Middleware
 *
 * A simple implementation of middleware that is built on top of Relay.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @link    http://relayphp.com
 */
class RelayMiddleware implements MiddlewareInterface
{
    /**
     * @var \Relay\RelayBuilder
     */
    protected $builder;

    /**
     * @param \Relay\RelayBuilder $builder
     */
    public function __construct(RelayBuilder $builder)
    {
        $this->builder = $builder;
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
        $middlewares = [];

        foreach ($queue as $class) {
            array_push($middlewares, new $class);
        }

        $middleware = $this->builder->newInstance($middlewares);

        return $middleware($request, $response);
    }
}
