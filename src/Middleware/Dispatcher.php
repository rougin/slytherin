<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;

/**
 * Dispatcher
 *
 * A sample implementation of the middleware dispatcher.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]
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
     * Returns the list of added middlewares.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $stack = (array) $this->getStack();

        $handler = new Handler($stack, $handler);

        return $handler->handle($request);
    }

    /**
     * Add a new middleware to the end of the stack.
     *
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
     * Sets a new stack of middlewares.
     *
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
     * Returns the list of added middlewares.
     * NOTE: To be removed in v1.0.0. Use "getStack" instead.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function stack()
    {
        return $this->getStack();
    }

    /**
     * Transforms the middleware into a Slytherin counterpart.
     *
     * @param  mixed $middleware
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface
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
     * Checks if the middleware is a callable.
     *
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
