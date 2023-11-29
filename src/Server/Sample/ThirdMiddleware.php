<?php

namespace Rougin\Slytherin\Server\Sample;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\MiddlewareInterface;

class ThirdMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = $handler->handle($request);

        $response->getBody()->write('Third!');

        return $response;
    }
}
