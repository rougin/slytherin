<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;

/**
 * Last Middleware
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LastMiddleware implements MiddlewareInterface
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface      $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null                            $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        if ($response->getBody() == '') {
            $response->getBody()->write('Loaded with middleware');
        } else {
            $response->getBody()->write(' Last!');
        }

        return $response;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = new Response;

        $response->getBody()->write('Loaded with middleware');

        return $response;
    }
}
