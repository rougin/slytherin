<?php

namespace Rougin\Slytherin\Previous\Handlers;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Hello
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable|null                            $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next = null)
    {
        if (! $next)
        {
            return $response;
        }

        $response = $next($request, $response);

        $response->getBody()->write('Hello from middleware');

        return $response;
    }
}
