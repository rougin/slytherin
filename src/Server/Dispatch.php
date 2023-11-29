<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

class Dispatch implements MiddlewareInterface
{
    protected $stack = array();

    public function __construct($stack = array())
    {
        $this->stack = $stack;
    }

    public function add($middleware)
    {
        $this->stack[] = $middleware;
    }

    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $handler = new Handler($this->stack, $handler);

        return $handler->handle($request);
    }
}
