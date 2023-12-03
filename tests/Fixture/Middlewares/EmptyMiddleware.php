<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class EmptyMiddleware implements MiddlewareInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        return $next($request, $response);
    }

    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        return $handler->handle($request);
    }
}
