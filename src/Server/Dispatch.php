<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatch implements DispatchInterface
{
    /**
     * @var mixed[]
     */
    protected $stack = array();

    /**
     * @param mixed[] $stack
     */
    public function __construct($stack = array())
    {
        if ($stack) $this->setStack($stack);
    }

    /**
     * @return \Rougin\Slytherin\Server\MiddlewareInterface[]
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $stack = array();

        foreach ($this->stack as $item)
        {
            $stack[] = $this->transform($item);
        }

        $handler = new Handler($stack, $handler);

        return $handler->handle($request);
    }

    /**
     * @param  mixed[] $stack
     * @return self
     */
    public function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * @param  mixed $middleware
     * @return \Rougin\Slytherin\Server\MiddlewareInterface
     */
    protected function transform($middleware)
    {
        $isClosure = is_a($middleware, 'Closure');

        if (! $isClosure) return new Wrapper($middleware);

        return new Callback($middleware);
    }
}
