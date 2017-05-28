<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Second Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class SecondMiddleware
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface      $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null                            $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        $response->getBody()->write(' Second!');

        return $next($request, $response);
    }
}
