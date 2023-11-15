<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * Interop Middleware
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class InteropMiddleware implements \Interop\Http\ServerMiddleware\MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request)->withStatus(500);

        return $response->withHeader('X-Slytherin', time());
    }
}
