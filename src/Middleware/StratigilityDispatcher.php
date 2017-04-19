<?php

namespace Rougin\Slytherin\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Zend\Stratigility\Middleware\CallableMiddlewareWrapper;

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
        $exists = class_exists('Zend\Stratigility\NoopFinalHandler');

        $handler = ($exists) ? new \Zend\Stratigility\NoopFinalHandler : null;

        $pipeline = $this->refine($this->stack);

        return $pipeline($request, $this->response, $handler);
    }

    /**
     * Prepares the stack to the pipeline.
     *
     * @param  array $stack
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function refine(array $stack)
    {
        $exists = class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapper');

        foreach ($stack as $class) {
            $callable = is_callable($class) ? $class : new $class;

            $callable = ($exists) ? new CallableMiddlewareWrapper($callable, $this->response) : $callable;

            $this->pipeline->pipe($callable);
        }

        return $this->pipeline;
    }
}
