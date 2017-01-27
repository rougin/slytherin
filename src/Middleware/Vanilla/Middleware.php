<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Vanilla Middleware
 *
 * A simple implementation of a middleware.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Middleware implements \Rougin\Slytherin\Middleware\MiddlewareInterface
{
    /**
     * @var array
     */
    protected $queue = array();

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
        $this->queue = $queue;

        $result = $this->resolve(0, $response);

        return $result($request, $response);
    }

    /**
     * Resolves the queue per index.
     *
     * @param  integer                             $index
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Rougin\Slytherin\Middleware\Vanilla\Delegate|null
     */
    protected function resolve($index, ResponseInterface $response)
    {
        if (! isset($this->queue[$index])) {
            return null;
        }

        $callable = function (ServerRequestInterface $request) use ($index, $response) {
            $current = $this->queue[$index];
            $current = class_exists($current) ? new $current : $current;

            $nextIndex = $this->resolve($index + 1, $response);

            return $current($request, $response, $nextIndex);
        };

        return new Delegate($callable);
    }
}
