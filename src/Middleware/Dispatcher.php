<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;

/**
 * A sample implementation of the middleware dispatcher.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Rougin\Slytherin\Middleware\Resolver
     */
    protected $resolver;

    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    protected $stack = array();

    /**
     * @param mixed[] $stack
     */
    public function __construct($stack = array())
    {
        $resolver = new Resolver(new Response);

        $this->resolver = $resolver;

        if ($stack)
        {
            $this->setStack($stack);
        }
    }

    /**
     * Returns the list of added middlewares.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Processes an incoming server request and return
     * a response, optionally delegating to the next
     * middleware component to create the response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface      $request
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $stack = $this->getStack();

        $handler = new Handler($stack, $handler);

        return $handler->handle($request);
    }

    /**
     * Adds a new middleware to the end of the stack.
     *
     * @param mixed $middleware
     *
     * @return self
     */
    public function push($middleware)
    {
        if (! is_array($middleware))
        {
            $item = $this->resolver->resolve($middleware);

            $this->stack[] = $item;

            return $this;
        }

        foreach ($middleware as $item)
        {
            $this->push($item);
        }

        return $this;
    }

    /**
     * Sets a new stack of middlewares.
     *
     * @param mixed[] $stack
     *
     * @return self
     */
    public function setStack($stack)
    {
        $result = array();

        foreach ($stack as $item)
        {
            $item = $this->resolver->resolve($item);

            $result[] = $item;
        }

        $this->stack = $result;

        return $this;
    }

    /**
     * @deprecated since ~0.9, use "getStack" instead.
     *
     * Returns the list of added middlewares.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function stack()
    {
        return $this->getStack();
    }
}
