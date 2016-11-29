<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
    protected $middleware;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $middleware
     */
    public function __construct(\Zend\Stratigility\MiddlewarePipe $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Processes the specified middlewares in queue.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @param  array                                    $queue
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $queue = [])
    {
        $middleware = $this->middleware;

        foreach ($queue as $class) {
            $middleware->pipe(new $class);
        }

        $out = null;

        if (class_exists('Zend\Stratigility\NoopFinalHandler')) {
            $out = new \Zend\Stratigility\NoopFinalHandler;
        }

        return $middleware($request, $response, $out);
    }
}
