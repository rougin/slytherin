<?php

namespace Rougin\Slytherin\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Zend\Stratigility\Middleware\CallableMiddlewareWrapper;
use Zend\Stratigility\Middleware\CallableInteropMiddlewareWrapper;

/**
 * Stratigility Dispatcher
 *
 * A simple implementation of middleware dispatcher that is built on top of Zend's Stratigility.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @link    https://github.com/zendframework/zend-stratigility
 */
class StratigilityDispatcher extends Dispatcher
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $pipeline;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $pipeline
     * @param array                             $stack
     */
    public function __construct(\Zend\Stratigility\MiddlewarePipe $pipeline, array $stack = array())
    {
        $this->pipeline = $pipeline;

        $this->stack = $stack;
    }

    /**
     * Process an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $this->convert($request, $delegate);

        foreach ($this->stack as $class) {
            $callable = $this->transform($class);

            $this->pipeline->pipe($callable);
        }

        if (! method_exists($this->pipeline, 'process')) {
            $exists = class_exists('Zend\Stratigility\NoopFinalHandler');

            $this->pipeline->pipe(new FinalResponse);

            $delegate = ($exists) ? new \Zend\Stratigility\NoopFinalHandler : null;
        }

        return $this->pipeline->__invoke($request, $response, $delegate);
    }

    /**
     * Converts a DelegateInterface to ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function convert(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);

        if (method_exists($this->pipeline, 'setResponsePrototype')) {
            $this->pipeline->setResponsePrototype($response);
        }

        $this->response = $response;

        return $response;
    }

    /**
     * Checks middleware if it needs to be instantiate or be wrapped.
     *
     * @param  callable|object|string $middleware
     * @return object|\Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function transform($middleware)
    {
        if (is_string($middleware) === false) {
            $name = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

            return (is_a($middleware, $name)) ? $middleware : $this->wrap($middleware);
        }

        return new $middleware;
    }

    /**
     * Wraps the callable from the list of available wrappers.
     *
     * @param  callable $class
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function wrap($class)
    {
        if (class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapper')) {
            $function = new \ReflectionFunction($class);

            if (count($function->getParameters()) == 3) {
                return new CallableMiddlewareWrapper($class, $this->response);
            }

            $class = new CallableInteropMiddlewareWrapper($class);
        }

        return $class;
    }
}
