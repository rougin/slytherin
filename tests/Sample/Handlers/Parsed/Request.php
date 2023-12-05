<?php

namespace Rougin\Slytherin\Sample\Handlers\Parsed;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Request implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $data = array('name' => 'Slytherin');

        $request = $request->withParsedBody($data);

        return $handler->handle($request);
    }
}
