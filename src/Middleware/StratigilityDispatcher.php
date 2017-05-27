<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Zend\Stratigility\MiddlewarePipe;

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
     * @var array
     */
    protected $stack = array();

    /**
     * @param \Zend\Stratigility\MiddlewarePipe        $pipeline
     * @param array                                    $stack
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(MiddlewarePipe $pipeline, array $stack = array(), ResponseInterface $response = null)
    {
        $this->pipeline = $pipeline;

        $this->response = $response ?: new \Rougin\Slytherin\Http\Response;

        $this->stack = $stack;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (method_exists($this->pipeline, 'setResponsePrototype')) {
            $this->pipeline->setResponsePrototype($this->response);
        }

        foreach ($this->stack as $class) {
            $item = $this->transform($class);

            $this->pipeline->pipe($item);
        }

        if (! method_exists($this->pipeline, 'process')) {
            $exists = class_exists('Zend\Stratigility\NoopFinalHandler');

            $this->pipeline->pipe(new FinalResponse);

            $delegate = ($exists) ? new \Zend\Stratigility\NoopFinalHandler : null;
        }

        return $this->pipeline->__invoke($request, $this->response, $delegate);
    }
}
