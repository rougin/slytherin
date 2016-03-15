<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Middleware Interface
 *
 * An interface for handling third party middlewares.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface MiddlewareInterface
{
    /**
     * Processes the specified middlewares in queue.
     * 
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  array $queue
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(Request $request, Response $response, array $queue = []);
}
