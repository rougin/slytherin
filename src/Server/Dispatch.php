<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatch implements MiddlewareInterface
{
    protected $stack = array();

    public function __construct($stack = array())
    {
        $this->stack = $stack;
    }

    public function getStack()
    {
        return $this->stack;
    }

    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $stack = array();

        foreach ($this->stack as $item)
        {
            array_push($stack, $this->transform($item));
        }

        $handler = new Handler($stack, $handler);

        return $handler->handle($request);
    }

    public function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    protected function transform($middleware)
    {
        $isClosure = is_a($middleware, 'Closure');

        if (! $isClosure) return new $middleware;

        return new Wrapper($middleware);
    }
}
