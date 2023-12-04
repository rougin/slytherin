<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;

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
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    protected $response = null;

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
     * @param  mixed $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (! is_array($middleware))
        {
            $item = $this->transform($middleware);

            array_push($this->stack, $item);

            return $this;
        }

        foreach ($middleware as $item) $this->push($item);

        return $this;
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
     * NOTE: To be removed in v1.0.0. Use $this->getStack() instead.
     *
     * @return \Rougin\Slytherin\Server\MiddlewareInterface[]
     */
    public function stack()
    {
        return $this->getStack();
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

        // Set empty response for double pass middlewares ---
        if (! $this->response)
        {
            $this->response = new Response;
        }
        // --------------------------------------------------

        if ($this->isCallable($middleware))
        {
            /** @var callable $middleware */
            return new Callback($middleware, $this->response);
        }

        return new Wrapper($middleware);
    }

    /**
     * @param  mixed $item
     * @return boolean
     */
    protected function isCallable($item)
    {
        /** @var object|string $item */
        $method = method_exists($item, '__invoke');

        $callable = is_callable($item);

        $object = is_object($item);

        $closure = (! $object) || $item instanceof \Closure;

        return ($method || $callable) && $closure;
    }
}
