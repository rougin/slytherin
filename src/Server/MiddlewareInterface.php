<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function getStack();

    public function process(ServerRequestInterface $request, HandlerInterface $handler);

    public function setStack($stack);
}
