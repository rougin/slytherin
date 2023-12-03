<?php

namespace Rougin\Slytherin\Sample\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ToJson implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = $handler->handle($request);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
