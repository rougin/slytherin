<?php

namespace Rougin\Slytherin\Middleware\Multiple;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Interop05 implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface         $request
     * @param \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** @var array<string, string> */
        $parsed = $request->getParsedBody();

        $response = $delegate->process($request);

        if (array_key_exists('name', $parsed))
        {
            $response = $response->withHeader('name', $parsed['name']);
        }

        return $response;
    }
}
