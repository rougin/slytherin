<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SecondMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        $response->getBody()->write(' Second!');

        return $next($request, $response);
    }
}
