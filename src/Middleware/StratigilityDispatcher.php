<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Stratigility Dispatcher
 *
 * A simple implementation of middleware dispatcher that is built on top of Zend's Stratigility.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/zendframework/zend-stratigility
 */
class StratigilityDispatcher extends Dispatcher
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $zend;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $pipe
     * @param mixed[]                           $stack
     */
    public function __construct(MiddlewarePipe $pipe, $stack = array())
    {
        parent::__construct($stack);

        $this->zend = $pipe;
    }

    /**
     * Checks if the current version has a wrapper factory.
     *
     * @return boolean
     */
    public function hasFactory()
    {
        return class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapperFactory');
    }

    /**
     * Checks if the current version implements the official PSR-15.
     *
     * @return boolean
     */
    public function hasPsr()
    {
        return class_exists('Zend\Stratigility\Middleware\CallableMiddlewareDecorator');
    }

    /**
     * Processes an incoming server request and return a response, optionally
     * delegating to the next middleware component to create the response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface      $request
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = new Response;

        $this->setFactory($response);

        foreach ($this->getStack() as $item)
        {
            $item = $this->setMiddleware($item);

            // @codeCoverageIgnoreStart
            if (! $this->hasFactory() && $this->hasPsr())
            {
                $item = $this->setPsrMiddleware($item);
            }
            // @codeCoverageIgnoreEnd

            /** @phpstan-ignore-next-line */
            $this->zend->pipe($item);
        }

        // Force version check to 1.0.0 if using v3.0 ---
        $version = $this->hasPsr() ? '1.0.0' : null;
        // ----------------------------------------------

        $next = Interop::getHandler($handler, $version);

        $zend = $this->zend;

        // @codeCoverageIgnoreStart
        if ($this->hasPsr())
        {
            /** @phpstan-ignore-next-line */
            return $zend->process($request, $next);
        }

        /** @phpstan-ignore-next-line */
        return $zend($request, $response, $next);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Sets the factory if there is a middleware decorator.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected function setFactory(ResponseInterface $response)
    {
        if (! $this->hasFactory())
        {
            return;
        }

        /** @var class-string */
        $factory = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapperFactory';

        $class = new \ReflectionClass((string) $factory);

        $factory = $class->newInstance($response);

        /** @var callable */
        $class = array($this->zend, 'setCallableMiddlewareDecorator');

        call_user_func_array($class, array($factory));
    }

    /**
     * Sets the Slytherin middleware into a single-pass or double-pass callable.
     *
     * @param \Rougin\Slytherin\Middleware\MiddlewareInterface $item
     *
     * @return callable
     *
     * @codeCoverageIgnore
     */
    protected function setMiddleware(MiddlewareInterface $item)
    {
        if ($this->hasPsr())
        {
            return function ($request, $handler) use ($item)
            {
                return $item->process($request, new Interop($handler));
            };
        }

        return function ($request, $response, $next) use ($item)
        {
            /** @var callable $next */
            $handle = new Doublepass($next, $response);

            return $item->process($request, $handle);
        };
    }

    /**
     * Sets the PSR-15 decorator to a middleware.
     *
     * @param callable $item
     *
     * @return object
     *
     * @codeCoverageIgnore
     */
    protected function setPsrMiddleware($item)
    {
        /** @var class-string */
        $psr = 'Zend\Stratigility\Middleware\CallableMiddlewareDecorator';

        $class = new \ReflectionClass($psr);

        return $class->newInstance($item);
    }
}
