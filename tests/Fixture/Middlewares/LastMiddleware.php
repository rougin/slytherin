<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Last Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class LastMiddleware
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null $next
     * @return callable|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($response->getBody() == '') {
            $response->getBody()->write('Loaded with middleware');
        } else {
            $result = (string) $response->getBody();

            $response->getBody()->write($result . ' Last!');
        }

        return $response;
    }
}
