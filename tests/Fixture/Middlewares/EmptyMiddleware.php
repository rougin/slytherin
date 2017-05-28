<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Empty Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class EmptyMiddleware
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface      $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null                            $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        return $next($request, $response);
    }
}
