<?php

namespace Rougin\Slytherin\Middleware\Multiple;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Slytherin implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface      $request
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        /** @var array<string, string> */
        $parsed = $request->getParsedBody();

        $parsed['name'] = 'Rougin Gutib';

        $request = $request->withParsedBody($parsed);

        return $handler->handle($request);
    }
}
