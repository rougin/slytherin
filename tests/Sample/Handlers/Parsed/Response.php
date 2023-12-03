<?php

namespace Rougin\Slytherin\Sample\Handlers\Parsed;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Response implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = $handler->handle($request);

        $response->getBody()->write('From middleware!');

        return $response;
    }
}
