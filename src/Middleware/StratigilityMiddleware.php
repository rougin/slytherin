<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Middleware\CallableMiddlewareWrapper;

/**
 * Stratigility Middleware
 *
 * A simple implementation of middleware that is built on top of
 * Zend Framework's Stratigility.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @link    https://github.com/zendframework/zend-stratigility
 */
class StratigilityMiddleware extends Middleware
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $pipeline;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $pipeline
     * @param array                             $stack
     */
    public function __construct(\Zend\Stratigility\MiddlewarePipe $pipeline, array $stack = array())
    {
        $this->pipeline = $pipeline;
        $this->stack    = $stack;
    }

    /**
     * Processes the specified middlewares in stack.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $stack
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $stack = array())
    {
        $hasHandler = class_exists('Zend\Stratigility\NoopFinalHandler');

        $handler  = ($hasHandler) ? new \Zend\Stratigility\NoopFinalHandler : null;
        $pipeline = $this->refine($stack, $response);

        return $pipeline($request, $response, $handler);
    }

    /**
     * Prepares the stack to the middleware.
     *
     * @param  array                               $stack
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function refine(array $stack, ResponseInterface $response)
    {
        $hasWrapper = class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapper');

        foreach ($stack as $class) {
            $callable = is_callable($class) ? $class : new $class;
            $callable = ($hasWrapper) ? new CallableMiddlewareWrapper($callable, $response) : $callable;

            $this->pipeline->pipe($callable);
        }

        return $this->pipeline;
    }
}
