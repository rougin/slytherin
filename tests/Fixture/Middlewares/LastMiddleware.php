<?php

namespace Rougin\Slytherin\Test\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
    public function __invoke(Request $request, Response $response, callable $next = null)
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
