<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\Interop;
use Rougin\Slytherin\Server\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Stratigility Dispatcher
 *
 * A simple implementation of middleware dispatcher that is built on top of Zend's Stratigility.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @link    https://github.com/zendframework/zend-stratigility
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
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = new Response;

        $this->setFactory($response);

        /** @var class-string */
        $psr = 'Zend\Stratigility\Middleware\CallableMiddlewareDecorator';

        foreach ($this->getStack() as $item)
        {
            $item = $this->setMiddleware($item);

            if (! $this->hasFactory())
            {
                $class = new \ReflectionClass($psr);

                $item = $class->newInstance($item);
            }

            $this->zend->pipe($item);
        }

        // Force version check to 1.0.0 if using v3.0 ---
        $version = class_exists($psr) ? '1.0.0' : null;
        // ----------------------------------------------

        $next = Interop::getHandler($handler, $version);

        $zend = $this->zend;

        if (class_exists($psr))
        {
            /** @phpstan-ignore-next-line */
            return $zend->process($request, $next);
        }

        /** @phpstan-ignore-next-line */
        return $zend($request, $response, $next);
    }

    /**
     * @return boolean
     */
    protected function hasFactory()
    {
        return class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapperFactory');
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    protected function setFactory(ResponseInterface $response)
    {
        if (! $this->hasFactory()) return;

        $factory = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapperFactory';

        $class = new \ReflectionClass((string) $factory);

        $factory = $class->newInstance($response);

        $class = array($this->zend, 'setCallableMiddlewareDecorator');

        call_user_func_array($class, array($factory));
    }

    /**
     * @param  \Rougin\Slytherin\Server\MiddlewareInterface $item
     * @return callable
     */
    protected function setMiddleware(MiddlewareInterface $item)
    {
        // Convert the handler into a callable if has a factory ----
        if ($this->hasFactory())
        {
            return function ($request, $response, $next) use ($item)
            {
                return $item->process($request, new Interop($next));
            };
        }
        // ---------------------------------------------------------

        return function ($request, $handler) use ($item)
        {
            return $item->process($request, new Interop($handler));
        };
    }
}
