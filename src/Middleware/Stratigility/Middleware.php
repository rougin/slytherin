<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

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
class Middleware implements \Rougin\Slytherin\Middleware\MiddlewareInterface
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $pipeline;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $pipeline
     */
    public function __construct(\Zend\Stratigility\MiddlewarePipe $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Processes the specified middlewares in queue.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $queue
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $queue = array())
    {
        $handler  = null;
        $pipeline = $this->prepareStack($queue, $response);

        if (class_exists('Zend\Stratigility\NoopFinalHandler')) {
            $handler = new \Zend\Stratigility\NoopFinalHandler;
        }

        return $pipeline($request, $response, $handler);
    }

    /**
     * Prepares the queue to the middleware.
     *
     * @param  array                               $queue
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function prepareStack(array $queue, ResponseInterface $response)
    {
        foreach ($queue as $class) {
            $callable = class_exists($class) ? new $class : $class;

            if (class_exists('Zend\Stratigility\Middleware\CallableMiddlewareWrapper')) {
                $callable = new CallableMiddlewareWrapper($callable, $response);
            }

            $this->pipeline->pipe($callable);
        }

        return $this->pipeline;
    }
}
