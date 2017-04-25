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
     * @param \Zend\Stratigility\MiddlewarePipe        $pipeline
     * @param array                                    $stack
     * @param \Psr\Http\Message\ResponseInterface|null $response -- NOTE: To be removed in v1.0.0. Use single pass instead.
     */
    public function __construct(\Zend\Stratigility\MiddlewarePipe $pipeline, array $stack = array(), ResponseInterface $response = null)
    {
        $this->pipeline = $pipeline;

        // NOTE: To be removed in v1.0.0. Use single pass instead.
        $this->response = $response ?: new \Rougin\Slytherin\Http\Response;

        $this->stack = $stack;

        if (method_exists($this->pipeline, 'setResponsePrototype')) {
            $this->pipeline->setResponsePrototype($this->response);
        }
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
        foreach ($this->stack as $class) {
            $string = ! is_callable($class) && is_string($class);

            $callable = ($string === true) ? new $class : $this->wrap($class);

            $this->pipeline->pipe($callable);
        }

        $pipeline = $this->pipeline;

        if (! method_exists($pipeline, 'process')) {
            $exists = class_exists('Zend\Stratigility\NoopFinalHandler');

            $handler = ($exists) ? new \Zend\Stratigility\NoopFinalHandler : null;

            return $pipeline($request, $this->response, $handler);
        }

        return $pipeline($request, $this->response, $delegate);
    }

    /**
     * Wraps the callable from the list of available wrappers.
     *
     * @param  callable|object $class
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface
     */
    protected function wrap($class)
    {
        $interface = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

        if (! is_callable($class) || is_a($class, $interface)) return $class;

        $wrapper = class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapper');

        if ($wrapper === false) return $class;

        $function = new \ReflectionFunction($class);

        if (count($function->getParameters()) == 3) {
            return new CallableMiddlewareWrapper($class, $this->response);
        }

        return new CallableInteropMiddlewareWrapper($class);
    }
}
