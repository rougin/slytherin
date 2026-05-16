<?php

namespace Rougin\Slytherin\Previous\Handlers;

/**
 * @deprecated since ~0.9, part of the "Previous" legacy test infrastructure.
 *
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
        // @codeCoverageIgnoreStart
        if (! $next)
        {
            return $response;
        }
        // @codeCoverageIgnoreEnd

        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $next($request, $response);

        $response->getBody()->write('Hello from middleware');

        /** @var \Psr\Http\Message\ResponseInterface */
        return $response;
    }
}
