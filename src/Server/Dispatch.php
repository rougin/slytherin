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
     * @var \Rougin\Slytherin\Server\MiddlewareInterface[]
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
        $stack = (array) $this->getStack();

        $handler = new Handler($stack, $handler);

        return $handler->handle($request);
    }

    /**
     * @param  mixed[] $stack
     * @return self
     */
    public function setStack($stack)
    {
        $result = array();

        foreach ($stack as $item)
        {
            $result[] = $this->transform($item);
        }

        $this->stack = $result;

        return $this;
    }

    /**
     * @param  mixed $middleware
     * @return \Rougin\Slytherin\Server\MiddlewareInterface
     */
    protected function transform($middleware)
    {
        if ($middleware instanceof MiddlewareInterface)
        {
            return $middleware;
        }

        $object = is_object($middleware);

        if ($object && is_a($middleware, 'Closure'))
        {
            return new Callback($middleware);
        }

        return new Wrapper($middleware);
    }
}
