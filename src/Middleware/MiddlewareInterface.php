<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * Processes the specified middlewares in stack.
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  array                               $stack
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array());

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function getQueue();
}
